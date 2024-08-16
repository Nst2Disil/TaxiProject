<?php
include '../backend/connectionDB.php';
$conn = mysqli_connect($servername, $username, null, $dbname);
session_start();

// Данные, введенные пользователем в форме
$username = $_POST["Username"];
$phone = $_POST["Phone"];
$email = $_POST["Email"];
$password = $_POST["Password"];
$name = $_POST["Name"];
$LicenseNum = $_POST["LicenseNum"];
$Passport = $_POST["Passport"];
$CarID = $_POST["CarID"];

// Подготовка SQL-запроса для добавления водителя
$sql1 = "INSERT INTO Users (Username, Password, Phone, Email, UserTypeID) VALUES (?, ?, ?, ?, 2)";

// Создание подготовленного выражения
if ($stmt1 = $conn->prepare($sql1)) {
    // Привязка параметров к подготовленному выражению
    $stmt1->bind_param("ssss", $username, $password, $phone, $email);

    // Выполнение подготовленного выражения
    if ($stmt1->execute()) {
        $last_inserted_id = $stmt1->insert_id; // Получаем автоинкрементный UserID

        // Подготовка SQL-запроса для добавления водителя в таблицу Drivers
        $sql2 = "INSERT INTO Drivers (UserID, Name, LicenseNum, Passport, CarID) VALUES (?, ?, ?, ?, ?)";

        // Создание подготовленного выражения
        if ($stmt2 = $conn->prepare($sql2)) {
            // Привязка параметров к подготовленному выражению
            $stmt2->bind_param("issss", $last_inserted_id, $name, $LicenseNum, $Passport, $CarID);

            // Выполнение подготовленного выражения
            if ($stmt2->execute()) {
                echo "Водитель '$username' успешно добавлен в базу данных.";
            } else {
                echo "Ошибка при добавлении водителя: " . $stmt2->error;
            }

            // Закрытие подготовленного выражения
            $stmt2->close();
        } else {
            echo "Ошибка при подготовке запроса: " . $conn->error;
        }
    } else {
        echo "Ошибка при добавлении водителя: " . $stmt1->error;
    }

    // Закрытие подготовленного выражения
    $stmt1->close();
} else {
    echo "Ошибка при подготовке запроса: " . $conn->error;
}

$conn->close();
