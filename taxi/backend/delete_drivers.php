<?php
include '../backend/connectionDB.php';
$conn = mysqli_connect($servername, $username, null, $dbname);
session_start();

// Получение введенных пользователем логинов
$drivers_to_delete = $_POST["drivers_to_delete"];

$driver_names = explode(",", $drivers_to_delete);

// SQL-запрос для удаления водителей с использованием подготовленных запросов
$sql = "DELETE FROM Users WHERE Username = ? AND UserTypeID = 2";
$stmt = $conn->prepare($sql);

if ($stmt) {
    foreach ($driver_names as $driver_name) {
        $driver_name = trim($driver_name);

        // Привязка параметра (логина водителя) к подготовленному выражению
        $stmt->bind_param("s", $driver_name);

        // Выполнение подготовленного запроса
        if ($stmt->execute()) {
            echo "Водитель '$driver_name' успешно удален из базы данных.<br>";
        } else {
            echo "Ошибка при удалении водителя '$driver_name': " . $stmt->error . "<br>";
        }
    }

    // Закрытие подготовленного выражения
    $stmt->close();
} else {
    echo "Ошибка при подготовке запроса: " . $conn->error . "<br>";
}

// Закрытие соединения с базой данных
$conn->close();
