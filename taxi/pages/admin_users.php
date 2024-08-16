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

        $sql = "SELECT * FROM AllClients";
        $result = $conn->query($sql);

        echo '<label class="titles">Список клиентов:</label><br><br>';
        if ($result->num_rows > 0) {
            // Проход по результатам выборки
            while ($row = $result->fetch_assoc()) {
                // Для каждой строки (пользователя) вывод информации
                echo "<div class='c-text'><u style='font-size: 1.1em;'> Имя:</u> " . $row["Username"] .
                    "<br><u style='font-size: 1.1em;'> Телефон:</u> " . $row["Phone"] .
                    "<br> <u style='font-size: 1.1em;'> Почта:</u> " . $row["Email"] .
                    "<br><u style='font-size: 1.1em;'> Пароль:</u> " . $row["Password"] .
                    "</div><br>";
            }
        } else {
            echo "Нет пользователей в БД.";
        }

        ?>

    </div>
    <div class="buttons-container">
        <input type="button" class="button" value="Вернуться назад" onclick="goBack()"><br><br>


        <!-- Кнопка "Добавить клиента(-ов)" с JS-обработчиком -->
        <input type="button" class="button" value="Добавить клиента(-ов)" onclick="toggleAddInput()">
        <!-- Поля для ввода данных клиентов, появляющееся при нажатии кнопки "Добавить клиента(-ов)" -->
        <div id="add-input-container" style="display: none;">
            <form action="../backend/add_clients.php" method="post"><br><br>
                <input type="text" name="Username" placeholder="Укажите имя клиента" style="width:210px"><br><br>
                <input type="text" name="Phone" placeholder="Укажите телефон" style="width:210px"><br><br>
                <input type="text" name="Email" placeholder="Укажите почту" style="width:210px"><br><br>
                <input type="text" name="Password" placeholder="Укажите пароль" style="width:210px"><br><br>
                <input type="submit" class="button" value="Добавить"><br><br>
            </form>
        </div>


        <!-- Кнопка "Удалить клиента(-ов)" с JS-обработчиком -->
        <br><br><input type="button" class="button" value="Удалить клиента(-ов)" onclick="toggleDeleteInput()">
        <!-- Поле для ввода клиентов, появляющееся при нажатии кнопки "Удалить клиента(-ов)" -->
        <div id="delete-input-container" style="display: none;">
            <form action="../backend/delete_clients.php" method="post"><br><br>
                <input type="text" name="clients_to_delete" placeholder="Укажите через запятую имена клиентов для удаления" style="width:410px"><br><br>
                <input type="submit" class="button" value="Удалить">
            </form>
        </div>
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
    </script>
    </div>
    <footer class="footer">

    </footer>
</body>

</html>