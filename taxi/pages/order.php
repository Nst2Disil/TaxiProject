<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>order</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/order.css">
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
    <main class="flex-container">
        <div class="cars-cont">
            <?php
            include '../backend/connectionDB.php';
            $conn = mysqli_connect($servername, $username, $password, $dbname);
            session_start();


            if (!empty($_COOKIE['username'])) {
                echo '<label class="hi">Добро пожаловать, ' . $_COOKIE["username"] . ' ! &#128075;</label>';

                // запрос для выборки всех классов автомобилей
                $sql1 = "SELECT * FROM AllCarsClasses";
                $result1 = $conn->query($sql1);

                // форма с классами автомобилей
                echo '<form action="order.php" method="post" id="car_class">
                      <label class="titles"><br>Выберите класс комфорта автомобиля: &#128200;</label><br><br>';

                // вывод радио-кнопок с классами комфорта автомобилей
                if ($result1->num_rows > 0) {
                    while ($row = $result1->fetch_assoc()) {
                        $className = $row['ClassName'];
                        if (isset($_POST['car_class'])) {
                            $checked = ($className == $_POST['car_class']) ? 'checked' : ''; // сохранение отображения выбранной радиокнопки

                            echo '<div class="c-c-text"><input onchange="submitCarClassForm()" type="radio" name="car_class" 
                                  value="' . $className . '" ' . $checked . '>' . $className . '</div><br>';
                        } else {
                            echo '<div class="c-c-text"><input onchange="submitCarClassForm()" type="radio" name="car_class" 
                                  value="' . $className . '" required>' . $className . '</div><br>';
                        }
                    }
                }
            }
            echo '</form>';
            ?>


            <script>
                // функция при изменении выбора класса автомобиля отправляет форму на сервер (для вывода списка машин данного класса)
                function submitCarClassForm() {
                    document.getElementById("car_class").submit();
                }
            </script>



            <div class="container">
                <form action="order_contin.php" method="post">
                    <?php
                    if (!empty($_POST['car_class']) && $_POST['car_class'] != 'null' || (!empty($_POST['selected_car_model']))) {
                        $selectedCarClass = $_POST['car_class'];

                        // запрос для выборки моделей выбранного класса комфорта
                        $sql2 = "SELECT CarModelsByComfortClass('$selectedCarClass') AS carModels";
                        $result2 = $conn->query($sql2);
                        echo '<label class="titles">Выберите модель автомобиля: &#128661;</label><br><br>
                          <select id="selected_car_model" name="selected_car_model">';

                        if ($result2->num_rows > 0) {
                            $row = $result2->fetch_assoc();
                            $carModelsStr = $row['carModels'];

                            // Разделяем строку на массив моделей
                            $carModels = explode(',', $carModelsStr);

                            // Перебираем модели в цикле и создаем для каждой элемент списка
                            foreach ($carModels as $carModel) {
                                echo "<option value='" . $carModel . "' >" . $carModel . "</option>";
                            }
                        }
                        echo '</select>';
                    }
                    ?>
            </div>
        </div>


        <div class="locations-dops">
            <label class="titles">Введите начальный и конечный адреса: &#128205;</label><br><br>
            <label for="start_loc" class="c-l-text">Откуда</label>
            <input type="text" id="start_loc" name="start_loc" required><br><br>
            <label for="end_loc" class="c-l-text">Куда</label>
            <input type="text" id="end_loc" name="end_loc" required>
            <div><br><br><br><br><br>
                <label class="titles">Детское кресло: &#127868;</label>
                <input type="checkbox" id="baby" name="baby"><br><br>
                <label class="titles">Молчаливый водитель: &#128263;</label>
                <input type="checkbox" id="silence" name="silence">
            </div>

        </div>
        <div class="button-container">
            <input type="submit" class="order-button" value="Продолжить">
        </div>
        </form>
        <input type="button" class="button" value="Вернуться назад" onclick="goBack()">
        <script>
            function goBack() {
                location.href = "user_page.php";
            }
        </script>
    </main>
    <footer class="footer">

    </footer>
</body>

</html>