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

        // представление для вывода информации о водителях и их машинах
        $sql = "SELECT * FROM AllDriversWithTheirCars";
        $result = $conn->query($sql);

        echo '<label class="titles">Список водителей:</label><br>';
        if ($result->num_rows > 0) {
            // Проход по результатам выборки
            while ($row = $result->fetch_assoc()) {
                // Для каждой строки (водителя) вывод информации
                echo "<div class='c-text'><u style='font-size: 1em;'> Логин:</u> " . $row["Username"] .
                    "<br><u style='font-size: 1em;'> Имя:</u> " . $row["Name"] .
                    "<br><u style='font-size: 1em;'> Телефон:</u> " . $row["Phone"] .
                    "<br> <u style='font-size: 1em;'> Почта:</u> " . $row["Email"] .
                    "<br><u style='font-size: 1em;'> Пароль:</u> " . $row["Password"] .
                    "<br><u style='font-size: 1em;'> Номер лицензии:</u> " . $row["LicenseNum"] .
                    "<br><u style='font-size: 1em;'> Паспорт:</u> " . $row["Passport"] .
                    "<br><u style='font-size: 1em;'> Рейтинг:</u> " . $row["Rating"] .
                    "<br><u style='font-size: 1.2em;'> Информация о машине</u>
                    <br><u style='font-size: 1em;'> VIN:</u> " . $row["VIN"] .
                    "<br><u style='font-size: 1em;'> Модель:</u> " . $row["CarModel"] .
                    "<br><u style='font-size: 1em;'> Класс комфорта:</u> " . $row["ClassName"] .
                    "<br><u style='font-size: 1em;'> Номер:</u> " . $row["CarNumber"] .
                    "<br><u style='font-size: 1em;'> Цвет:</u> " . $row["ColorName"] .
                    "</div><br>";
            }
        } else {
            echo "Нет водителей в БД.";
        }

        ?>

    </div>
    <div class="buttons-container">
        <input type="button" class="button" value="Вернуться назад" onclick="goBack()"><br><br>


        <!-- Кнопка добавления с JS-обработчиком -->
        <input type="button" class="button" value="Добавить водителя" onclick="toggleAddInput()">
        <!-- Поля для ввода данных водителей, появляющееся при нажатии кнопки -->
        <div id="add-input-container" style="display: none;">
            <form action="../backend/add_drivers.php" method="post"><br><br>
                <input type="text" name="Username" placeholder="Логин водителя" style="width:210px"><br><br>
                <input type="text" name="Name" placeholder="Имя водителя" style="width:210px"><br><br>
                <input type="text" name="Phone" placeholder="Телефон" style="width:210px"><br><br>
                <input type="text" name="Email" placeholder="Почта" style="width:210px"><br><br>
                <input type="text" name="Password" placeholder="Пароль" style="width:210px"><br><br>
                <input type="text" name="LicenseNum" placeholder="Номер лицензии" style="width:210px"><br><br>
                <input type="text" name="Passport" placeholder="Паспорт" style="width:210px"><br><br>
                <input type="text" name="CarID" placeholder="ID машины" style="width:210px"><br><br>
                <input type="submit" class="button" value="Добавить"><br><br>
            </form>
        </div>


        <!-- Кнопка удаления с JS-обработчиком -->
        <br><br><input type="button" class="button" value="Удалить водителя(-ей)" onclick="toggleDeleteInput()">
        <!-- Поле для ввода водителей, появляющееся при нажатии кнопки -->
        <div id="delete-input-container" style="display: none;">
            <form action="../backend/delete_drivers.php" method="post"><br><br>
                <input type="text" name="drivers_to_delete" placeholder="Укажите через запятую логины водителей для удаления" style="width:430px"><br><br>
                <input type="submit" class="button" value="Удалить">
            </form>
        </div>

        <br><br><input type="button" class="button" value="Диаграмма рейтингов водителей" onclick="redirectToRatingPage()">
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

        function toggleAddInput() {
            var addInputContainer = document.getElementById("add-input-container");
            if (addInputContainer.style.display === "none") {
                addInputContainer.style.display = "block";
            } else {
                addInputContainer.style.display = "none";
            }
        }

        function redirectToRatingPage() {
            location.href = "rating_graph.php";
        }
    </script>
    </div>
    <footer class="footer">

    </footer>
</body>

</html>