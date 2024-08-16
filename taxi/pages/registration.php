<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sign-in</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/registration.css">
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


    <div class="reg_l"><label class="r-f-text">Регистрация</label><br><br></div>
    <div class="main">
        <div class="reg_form">

            <?php
            include '../backend/connectionDB.php';
            $conn = mysqli_connect($servername, $username, $password, $dbname);

            echo '<br><br><form action="registration.php" method="post">
            <label for="username" class="r-f-text">Логин:</label>
            <input type="text" name="username" required><br><br>
            <label for="phone" class="r-f-text">Телефон:</label>
            <input type="text" name="phone" required><br><br>
            <label for="email" class="r-f-text">Почта:</label>
            <input type="text" name="email" required><br><br>
            <label for="password" class="r-f-text">Придумайте пароль:</label>
            <input type="text" name="password" required><br><br>
            <label for="password" class="r-f-text">Повторите пароль:</label>
            <input type="text" name="password2" required><br><br><br>
            <input type="submit" value="Зарегистрироваться" style="font-size:2vh;">
            </form>';

            session_start();

            if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password2'])) {
                $username = $_POST["username"];

                // Проверка наличия пользователя с таким именем в БД
                $sql_check_user = "SELECT UserID FROM Users WHERE Username = ? AND UserTypeID = 1";

                // Используйте подготовленный запрос для проверки
                if ($stmt = $conn->prepare($sql_check_user)) {
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->store_result();

                    // Если найден пользователь с таким логином, выводим сообщение
                    if ($stmt->num_rows > 0) {
                        echo "<br><br>Пользователь с таким именем уже существует. Пожалуйста, выберите другой логин.";
                        $stmt->close();
                    } else {
                        $stmt->close();

                        if ($_POST['password'] != $_POST['password2']) {
                            echo '<br><br>Пароли не совпадают! Повторите попытку.';
                        } else {
                            $username = $_POST['username'];
                            $password = $_POST['password'];
                            $phone = $_POST['phone'];
                            $email = $_POST['email'];

                            // Подготовка SQL-запроса для добавления пользователя
                            $sql_register_user = "INSERT INTO Users (Username, Password, Phone, Email, UserTypeID) VALUES (?, ?, ?, ?, 1)";

                            // Используйте подготовленный запрос для регистрации
                            if ($stmt = $conn->prepare($sql_register_user)) {
                                $stmt->bind_param("ssss", $username, $password, $phone, $email);
                                if ($stmt->execute()) {
                                    $last_inserted_id = $stmt->insert_id; // Получаем автоинкрементный UserID нового пользователя
                                    echo "<br><br>Вы успешно зарегистрированы! Можете осуществить вход через меню";
                                } else {
                                    echo "<br><br>Ошибка при регистрации пользователя: " . $stmt->error;
                                }
                                $stmt->close();
                            } else {
                                echo "<br><br>Ошибка при подготовке запроса: " . $conn->error;
                            }
                        }
                    }
                } else {
                    echo "<br><br>Ошибка при подготовке запроса: " . $conn->error;
                }
            }
            ?>

        </div>
    </div>


    <footer class="footer">

    </footer>
</body>

</html>