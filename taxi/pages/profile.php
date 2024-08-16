<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/profile.css">
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

    <div class="profile-container">
        <div class="profile-photo">
            <img src="../img/profile.jpg" alt="photo" width="450vw" height="450vh">
        </div>
        <?php
        include '../backend/connectionDB.php';
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        session_start();

        $uv = $_COOKIE['username'];

        // Подготовленный запрос для вызова процедуры
        $stmt = $conn->prepare("CALL GetProfileInfoByUsername(:username)");
        $stmt->bindParam(':username', $uv, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo '<div class="container">
              <div class="user-info">
                <label class="p-i-text">Имя: </label>' . $result['Name'] . '<br><br>
                <label class="p-i-text">Телефон: </label>' . $result['Phone'] . '<br><br>
                <label class="p-i-text">Почта: </label>' . $result['Email'] . '<br><br>
                <label class="p-i-text">Номер лицензии: </label>' . $result['LicenseNum'] . '<br><br>
                <label class="p-i-text">Паспорт: </label>' . $result['Passport'] . '<br><br>
                <label class="p-i-text">Рейтинг: </label>' . $result['Rating'] . '<br><br>
                <!-- Добавляем надпись, которую можно кликнуть -->
                <label class="p-i-text-change" id="car-info-toggle">Информация о машине &#8599;</label>
              </div>

              <div class="car-info">

                <!-- Блок с информацией о машине (изначально скрыт) -->
                <div id="car-info" style="display: none;">
                    <div class="car_info">
                        <label class="p-i-car">VIN: </label>' .  $result['VIN'] . '<br><br>
                        <label class="p-i-car">Модель: </label>' . $result['CarModel'] . '<br><br>
                        <label class="p-i-car">Класс комфорта: </label>' . $result['ClassName'] . '<br><br>
                        <label class="p-i-car">Номер: </label>' . $result['CarNumber'] . '<br><br>
                        <label class="p-i-car">Цвет: </label>' . $result['ColorName'] . '<br><br>
                    </div>
                </div>
              </div>
        </div>';
        } else {
            echo 'Информации о пользователе нет в базе';
        }
        ?>

        <input type="button" class="button" value="Вернуться назад" onclick="goBack()">
        <script>
            function goBack() {
                location.href = "driver_page.php";
            }
        </script>
    </div>


    <footer class="footer">

    </footer>


    <script>
        // скрипт для изменения видимости блока с информацией о машине
        // Получаем ссылки на элементы
        const carInfoToggle = document.getElementById("car-info-toggle");
        const carInfo = document.getElementById("car-info");

        // Добавляем обработчик события клика на надписи
        carInfoToggle.addEventListener("click", function() {
            // При клике переключаем видимость блока с информацией
            if (carInfo.style.display === "none") {
                carInfo.style.display = "block"; // Показываем блок
            } else {
                carInfo.style.display = "none"; // Скрываем блок
            }
        });
    </script>


</body>

</html>