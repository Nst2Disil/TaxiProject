<?php
include '../backend/connectionDB.php';

$orderId = $_POST['orderId'];
$rating = $_POST['rating_' . $orderId];

$conn = mysqli_connect($servername, $username, null, $dbname);

// Подготовленный запрос для вставки оценки Mark в таблицу Orders
$updateSql = "UPDATE Orders SET Mark = ? WHERE OrderID = ?";

$stmt = $conn->prepare($updateSql);

if ($stmt) {
    $stmt->bind_param("di", $rating, $orderId);

    if ($stmt->execute()) {
        // Оценка добавлена в базу данных Orders
        echo "Оценка добавлена в базу данных!&#11088;";
        $stmt->close();

        // Получаем DriverID для данного заказа
        $getDriverIdSql = "SELECT DriverID FROM Orders WHERE OrderID = ?";
        $stmt = $conn->prepare($getDriverIdSql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $stmt->bind_result($driverId);
        $stmt->fetch();
        $stmt->close();

        // Получаем текущий Rating водителя из таблицы Drivers
        $getDriverRatingSql = "SELECT Rating FROM Drivers WHERE DriverID = ?";
        $stmt = $conn->prepare($getDriverRatingSql);
        $stmt->bind_param("i", $driverId);
        $stmt->execute();
        $stmt->bind_result($currentRating);
        $stmt->fetch();
        $stmt->close();

        // Вычисляем новое значение Rating как среднее между текущим Rating и новой оценкой
        $newRating = ($currentRating + $rating) / 2;

        // Обновляем Rating водителя в таблице Drivers
        $updateDriverRatingSql = "UPDATE Drivers SET Rating = ? WHERE DriverID = ?";
        $stmt = $conn->prepare($updateDriverRatingSql);
        $stmt->bind_param("di", $newRating, $driverId);

        if ($stmt->execute()) {
            // Rating водителя обновлен успешно
            echo "<br>Благодаря вашей оценке рейтинг водителя обновлен!&#128202;";
        } else {
            echo "Ошибка при обновлении Rating водителя: " . $stmt->error;
        }
    } else {
        echo "Ошибка при выполнении запроса: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Ошибка при подготовке запроса: " . $conn->error;
}

$conn->close();
