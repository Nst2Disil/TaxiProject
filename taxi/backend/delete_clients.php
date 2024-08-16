<?php
include '../backend/connectionDB.php';
$conn = mysqli_connect($servername, $username, null, $dbname);
session_start();

// Получение введенных пользователем имён клиентов
$clients_to_delete = $_POST["clients_to_delete"];

// Разделение имён клиентов
$client_names = explode(",", $clients_to_delete);

// SQL-запрос для удаления клиентов с использованием подготовленных запросов
$sql = "DELETE FROM Users WHERE Username = ? AND UserTypeID = 1";
$stmt = $conn->prepare($sql);

if ($stmt) {
    foreach ($client_names as $client_name) {
        $client_name = trim($client_name); // Удаление лишних пробелов

        // Привязка параметра (имени клиента) к подготовленному выражению
        $stmt->bind_param("s", $client_name);

        // Выполнение подготовленного запроса для удаления клиента
        if ($stmt->execute()) {
            echo "Клиент '$client_name' успешно удален из базы данных.<br>";
        } else {
            echo "Ошибка при удалении клиента '$client_name': " . $stmt->error . "<br>";
        }
    }

    // Закрытие подготовленного выражения
    $stmt->close();
} else {
    echo "Ошибка при подготовке запроса: " . $conn->error . "<br>";
}

// Закрытие соединения с базой данных
$conn->close();
