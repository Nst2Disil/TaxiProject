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
        $conn = mysqli_connect($servername, $username, $password, $dbname );
        session_start();

        $id = $_SESSION['UserID'];

        // Подготовленный запрос для вызова процедуры
        $query = "CALL GetOrdersByDriverID(?)";

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
                        echo "<div class='c-text'><u style='font-size: 1.1em;'> Откуда:</u> " . $row["StartLocation"] .
                            "<br><u style='font-size: 1.1em;'> Куда:</u> " . $row["EndLocation"] .
                            "<br> <u style='font-size: 1.1em;'> Детское кресло:</u> " . $row["BabyChair"] .
                            "<br><u style='font-size: 1.1em;'> Молчаливый водитель:</u> " . $row["SilentDriver"] .
                            "<br><u style='font-size: 1.1em;'> Способ оплаты:</u> " . $row["PaymentMethodName"] .
                            "</div><br>";
                    }
                } else {
                    echo "Пока нет заказов.";
                }
                // Освобождаем результат
                $result->free();
            } else {
                echo "Ошибка при выполнении запроса: " . $stmt->error;
            }
            // Закрываем подготовленный запрос
            $stmt->close();
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
            location.href = "driver_page.php";
        }
    </script>

    <footer class="footer">
    </footer>
</body>

</html>