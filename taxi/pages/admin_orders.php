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

        $sql = "SELECT * FROM AllOrders";
        $result = $conn->query($sql);

        echo '<label class="titles">Список заказов:</label><br>';
        if ($result->num_rows > 0) {
            // Проход по результатам выборки
            while ($row = $result->fetch_assoc()) {
                // Для каждой строки вывод информации
                echo "<div class='c-text'><u style='font-size: 1em;'> ID заказа:</u> " . $row["OrderID"] .
                    "<br><u style='font-size: 1em;'> ID клиента:</u> " . $row["UserID"] .
                    "<br><u style='font-size: 1em;'> ID водителя:</u> " . $row["DriverID"] .
                    "<br> <u style='font-size: 1em;'> Начальное местоположение:</u> " . $row["StartLocation"] .
                    "<br><u style='font-size: 1em;'> Конечное местоположение:</u> " . $row["EndLocation"] .
                    "<br><u style='font-size: 1em;'> Детское кресло:</u> " . $row["BabyChair"] .
                    "<br><u style='font-size: 1em;'> Молчаливый водитель:</u> " . $row["SilentDriver"] .
                    "<br><u style='font-size: 1em;'> ID способа оплаты:</u> " . $row["PaymentMethodID"] .
                    "<br><u style='font-size: 1em;'> Оценка пользователя:</u> " . $row["Mark"] . "&#11088;
                    </div><br>";
            }
        } else {
            echo "Нет заказов в БД.";
        }

        ?>

    </div>
    <div class="buttons-container">
        <input type="button" class="button" value="Вернуться назад" onclick="goBack()">


        <!-- Кнопка удаления-->
        <br><br><input type="button" class="button" value="Удалить заказ(-ы)" onclick="toggleDeleteInput()">
        <!-- Поле для ввода заказов, появляющееся при нажатии кнопки -->
        <div id="delete-input-container" style="display: none;">
            <form action="../backend/delete_orders.php" method="post"><br><br>
                <input type="text" name="orders_to_delete" placeholder="Укажите через запятую ID заказов для удаления" style="width:410px"><br><br>
                <input type="submit" class="button" value="Удалить">
            </form>
        </div>



        <script>
            function goBack() {
                location.href = "admin_page.php";
            }

            function toggleDeleteInput() {
                var deleteInputContainer = document.getElementById("delete-input-container");
                if (deleteInputContainer.style.display === "none") {
                    deleteInputContainer.style.display = "block";
                } else {
                    deleteInputContainer.style.display = "none";
                }
            }
        </script>
    </div>
    <footer class="footer">

    </footer>
</body>

</html>