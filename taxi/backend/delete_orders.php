<?php
include '../backend/connectionDB.php';
$conn = mysqli_connect($servername, $username, null, $dbname);
session_start();

// Получение введенных пользователем имён клиентов
$orders_to_delete = $_POST["orders_to_delete"];

// Разделение ID заказов
$ordsers_id = explode(",", $orders_to_delete);

// SQL-запрос для удаления заказов с использованием подготовленных запросов
$sql = "DELETE FROM Orders WHERE OrderID = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    foreach ($ordsers_id as $order_id) {
        $order_id = trim($order_id); // Удаление лишних пробелов

        // Привязка параметра к подготовленному выражению
        $stmt->bind_param("i", $order_id);

        // Выполнение подготовленного запроса для удаления заказа
        if ($stmt->execute()) {
            echo "Заказ '$order_id' успешно удален из базы данных.<br>";
        } else {
            echo "Ошибка при удалении заказа '$order_id': " . $stmt->error . "<br>";
        }
    }

    // Закрытие подготовленного выражения
    $stmt->close();
} else {
    echo "Ошибка при подготовке запроса: " . $conn->error . "<br>";
}

// Закрытие соединения с базой данных
$conn->close();
