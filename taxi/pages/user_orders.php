<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>order</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/admin_users.css">
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

    <div class="container">
        <?php
        include '../backend/connectionDB.php';
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        session_start();

        $id = $_SESSION['UserID'];

        // Подготовленный запрос для вызова процедуры
        $query = "CALL GetOrdersByUserID(?)";

        if ($stmt = $conn->prepare($query)) {
            // Привязка параметра
            $stmt->bind_param("i", $id);

            // Выполнение запроса
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                echo '<label class="titles">Мои заказы:</label><br><br>';
                if ($result->num_rows > 0) {
                    // Проход по результатам выборки
                    while ($row = $result->fetch_assoc()) {
                        // Для каждой строки вывод информации
                        $orderId = $row["OrderID"];
                        $mark = $row["Mark"];

                        echo "<div class='c-text'><u style='font-size: 1.1em;'> Откуда:</u> " . $row["StartLocation"] .
                            "<br><u style='font-size: 1.1em;'> Куда:</u> " . $row["EndLocation"] .
                            "<br> <u style='font-size: 1.1em;'> Детское кресло:</u> " . $row["BabyChair"] .
                            "<br><u style='font-size: 1.1em;'> Молчаливый водитель:</u> " . $row["SilentDriver"] .
                            "<br><u style='font-size: 1.1em;'> Способ оплаты:</u> " . $row["PaymentMethodName"] .
                            "<br><u style='font-size: 1.1em;'> Оцените поездку:</u> ";

                        if ($mark !== null) {
                            // Если оценка уже была выбрана, отображаем ее
                            echo "Оценка: $mark&#11088;";
                        } else {
                            // В противном случае, отображаем радиокнопки
                            echo '<form name="ratingForm_' . $orderId . '" method="POST" action="../backend/update_mark_and_rating.php">
                            <input type="hidden" name="orderId" value="' . $orderId . '">
                            <input type="radio" name="rating_' . $orderId . '" value="1" onclick="showThankYou(this)"> 1&#11088;
                            <input type="radio" name="rating_' . $orderId . '" value="2" onclick="showThankYou(this)"> 2&#11088;
                            <input type="radio" name="rating_' . $orderId . '" value="3" onclick="showThankYou(this)"> 3&#11088;
                            <input type="radio" name="rating_' . $orderId . '" value="4" onclick="showThankYou(this)"> 4&#11088;
                            <input type="radio" name="rating_' . $orderId . '" value="5" onclick="showThankYou(this)"> 5&#11088;
                            </form>';
                        }

                        echo "</div><br>";
                    }
                } else {
                    echo "Пока нет заказов.";
                }
                // Освобождаем результат
                $result->free();
            } else {
                echo "Ошибка при выполнении запроса: " . $stmt->error;
            }
        } else {
            echo "Ошибка при подготовке запроса: " . $conn->error;
        }

        $conn->close();
        ?>
    </div>

    <div class="buttons-container">
        <input type="button" class="button" value="Вернуться назад" onclick="goBack()"><br><br>
    </div>

    <script>
        function goBack() {
            location.href = "user_page.php";
        }

        // функция для отправки формы
        function showThankYou(radio) {
            var orderId = radio.name.split('_')[1]; // Имя радиокнопки имеет форму rating_orderId; .split('_')[1] извлекает orderId.

            // Отправка формы на сервер для обновления данных в базе данных
            document.forms['ratingForm_' + orderId].submit();
        }
    </script>

    <footer class="footer">
    </footer>
</body>

</html>