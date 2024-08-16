<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>order</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/admin_users.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
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
    include '../backend/connectionDB.php';
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    $sql = "SELECT * FROM AllDriversRatings";
    $result = $conn->query($sql);

    $data = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $data[] = $r;
    }
    $drivers = array();
    $ratings = array();

    foreach ($data as $row) {
        $drivers[] = $row['DriverID'];
        $ratings[] = $row['Rating'];
    }
    ?>

    <div style="width: 70%;  margin: 0 auto; padding-top: 4vh;">
        <canvas id="myChart"></canvas>
    </div>

    <script>
        // Данные о водителях и их рейтингах
        var drivers = <?php echo json_encode($drivers); ?>;
        var ratings = <?php echo json_encode($ratings); ?>;

        // Настройка графика
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar', // Тип графика (столбчатая диаграмма)
            data: {
                labels: drivers, // Метки на оси x (ID водителей)
                datasets: [{
                    label: 'Рейтинг',
                    data: ratings, // Данные (рейтинги)
                    backgroundColor: 'rgba(55, 192, 255, 0.2)', // Цвет столбцов
                    borderColor: 'rgba(75, 192, 192, 1)', // Цвет обводки столбцов
                    borderWidth: 1 // Ширина обводки столбцов
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Рейтинг'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'ID водителя'
                        }
                    }
                }
            }
        });
    </script>


    <footer class="footer">

    </footer>
</body>

</html>