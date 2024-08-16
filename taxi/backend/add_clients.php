<?php
include '../backend/connectionDB.php';
$conn = mysqli_connect($servername, $username, null, $dbname);
session_start();

// Данные, введенные пользователем в форме
$username = $_POST["Username"];
$phone = $_POST["Phone"];
$email = $_POST["Email"];
$password = $_POST["Password"];

// Подготовка SQL-запроса для добавления пользователя с использованием подготовленного выражения
$sql = "INSERT INTO Users (Username, Password, Phone, Email, UserTypeID) VALUES (?, ?, ?, ?, 1)";

// Создание подготовленного выражения
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Привязка параметров к подготовленному выражению
    $stmt->bind_param("ssss", $username, $password, $phone, $email);

    // Выполнение подготовленного запроса
    if ($stmt->execute()) {
        $last_inserted_id = $stmt->insert_id; // Получаем автоинкрементный UserID нового пользователя
        echo "Клиент '$username' успешно добавлен в базу данных.";
    } else {
        echo "Ошибка при добавлении клиента: " . $stmt->error;
    }

    // Закрытие подготовленного выражения
    $stmt->close();
} else {
    echo "Ошибка при подготовке запроса: " . $conn->error;
}

$conn->close();
