<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sign-in</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/user_log.css">
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

    <div class="user_form">
        <label class="u-f-text">Авторизация</label><br><br>


        <?php
        session_start();
        include '../backend/connectionDB.php';
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        echo '
        <form action="admin_log.php" method="post">
        <label for="username" class="u-f-text">Логин:</label>
        <input type="text" name="username"><br><br>
        <label for="password" class="u-f-text">Пароль:</label>
        <input type="text" name="password"><br><br>
        <input type="submit" value="Войти" style="font-size:2vh;">
        </form>
        ';

        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Подгототвка SQL-запроса с использованием PDO
            $sql = "SELECT GetAdminByUsernameAndPassword(:username, :password) AS user_id";
            $stmt = $conn->prepare($sql);

            // Передача параметров в SQL-запрос
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);

            // Выполнение запроса
            $stmt->execute(array(':username' => $username, ':password' => $password));


            // Полчение результата запроса
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_id = $result['user_id'];


            // проверка корректности логина и пароля
            if (trim($username) == '')
                echo '<br>Введите имя пользователя';
            else if ($user_id) {
                // сохранение информации об авторизации пользователя
                setcookie('username', $username, time() + 3600);
                setcookie('password', $password, time() + 3600);
                // переадресация пользователя на другую страницу
                header('Location: admin_page.php');

                // Устанавливаем UserID в сессии
                $_SESSION['UserID'] = $user_id;
            } else {
                echo '<br>Неверный пользователь или пароль';
            }
        }
        ?>
    </div>


    <footer class="footer">

    </footer>
</body>

</html>