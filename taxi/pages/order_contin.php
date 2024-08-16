<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>order</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/order_contin.css">
</head>

<body>
    <nav class="nav_main">
        <div class="content">
            <img src="../img/logo.png" class="logo">
            <ul class="c-list">
                <li>
                    <a class="nav_text" href="../index.php"> Главная страница </a>
                </li>
                <li>
                    <a class="nav_text" href="about.php"> О приложении </a>
                </li>
                <li>
                    <a class="nav_text" href="login.php">Войти</a>
                </li>
                <li>
                    <a class="nav_text" href="registration.php">Зарегистрироваться</a>
                </li>
            </ul>
        </div>
    </nav>

    <?php
    session_start();
    include '../backend/connectionDB.php';
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // создание переменных сессии
    if (isset($_POST['selected_car_model'])) {
        $_SESSION['selected_car_model'] = $_POST['selected_car_model'];
    }
    if (isset($_POST['start_loc'])) {
        $_SESSION['start_loc'] = $_POST['start_loc'];
    }

    if (isset($_POST['end_loc'])) {
        $_SESSION['end_loc'] = $_POST['end_loc'];
    }


    // вызов процедуры для вывода информации о заказе через выбранную модель машины 
    $sql = "CALL GetOrderInfoByCarName(?)";

    // Подготовленный запрос
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['selected_car_model']);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='container'><div class='c-text'><u style='font-size: 1em;'> Ваш путь: </u> " . $_SESSION['start_loc'] . " &#10145; &#10145; &#10145; " . $_SESSION['end_loc'] .
                    "<br><u style='font-size: 1em;'> Ваше такси:</u> " . $_SESSION['selected_car_model'] .
                    "<br><u style='font-size: 1em;'> Цвет:</u> " . $row['ColorName'] .
                    "<br> <u style='font-size: 1em;'> Номер машины:</u> " . $row['CarNumber'] .
                    "<br><u style='font-size: 1em;'> Ваш водитель:</u> "  . $row['Name'] .
                    "<br><u style='font-size: 1em;'> Рейтинг водителя:</u> " . $row['Rating'];
                if (isset($_POST["baby"]) && isset($_POST["silence"])) {
                    $_SESSION['BabyChair'] = 1;
                    $_SESSION['SilentDriver'] = 1;
                    echo '<br><u style="font-size: 1em;">Дополнительные опции:</u> детское кресло; молчаливый водитель.' . '<br><br>';
                } else if (isset($_POST["silence"])) {
                    $_SESSION['SilentDriver'] = 1;
                    echo '<br><u style="font-size: 1em;">Дополнительные опции:</u> молчаливый водитель' . '<br><br>';
                } else if (isset($_POST["baby"])) {
                    $_SESSION['BabyChair'] = 1;
                    echo '<br><u style="font-size: 1em;">Дополнительные опции:</u> детское кресло' . '<br><br>';
                }

                $_SESSION['DriverID'] = $row['DriverID'];

                echo '<br><br><br><u style="font-size: 1em;">Выберите способ оплаты:</u>' . '<br><br>';

                // Закрытие подготовленного запроса
                $stmt->close();

                // Запрос для выборки всех способов оплаты
                echo '<form action="order_contin.php" method="post">';
                $sql = "SELECT * FROM PaymentMethods";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $paymentMethodName = $row['PaymentMethodName'];

                        // Вывод радио-кнопок для каждого способа оплаты
                        echo '<div class=""><input type="radio" name="payment_method" id="payment_method"
              value="' . $paymentMethodName . '">' . $paymentMethodName . '</div><br>';
                    }
                } else {
                    echo "Нет доступных способов оплаты.";
                }
                echo "</div>";
                echo '<div class="buttons-container">
              <input type="button" class="button" value="Вернуться назад" onclick="goBack()">
              <input type="submit" class="button" value="Подтвердить заказ">
              </div>';
                echo '</form>';

                echo '</div>';

                if (isset($_POST['payment_method'])) {
                    $paymentMethod = $_POST['payment_method'];
                    $sql2 = "SELECT PaymentMethodID FROM PaymentMethods WHERE PaymentMethodName = '$paymentMethod'";

                    $result2 = $conn->query($sql2);

                    if ($result2->num_rows > 0) {
                        while ($row = $result2->fetch_assoc()) {
                            $paymentMethodID = $row['PaymentMethodID'];
                        }
                    }

                    // Подготовленный запрос
                    $sql = "INSERT INTO Orders (UserID, DriverID, StartLocation, EndLocation, PaymentMethodID, BabyChair, SilentDriver) VALUES (?, ?, ?, ?, ?, ?, ?)";

                    // Подготовка запроса
                    $stmt = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt, $sql)) {
                        // Привязка параметров и выполнение запроса
                        mysqli_stmt_bind_param($stmt, "iisssii", $_SESSION['UserID'], $_SESSION['DriverID'], $_SESSION['start_loc'], $_SESSION['end_loc'], $paymentMethodID, $_SESSION['BabyChair'], $_SESSION['SilentDriver']);

                        if (mysqli_stmt_execute($stmt)) {
                            echo "Данные успешно внесены в базу данных.";
                        } else {
                            echo "Ошибка при выполнении запроса: " . mysqli_error($conn);
                        }

                        // Закрытие запроса
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "Ошибка при подготовке запроса: " . mysqli_error($conn);
                    }

                    // Закрытие соединения с базой данных
                    mysqli_close($conn);
                }

                // Ваши действия с полученными данными
            }
        } else {
            echo "Данные не найдены.";
        }
    }

    ?>

    <script>
        function goBack() {
            location.href = "order.php";
        }
    </script>

    <footer class="footer">

    </footer>
</body>

</html>