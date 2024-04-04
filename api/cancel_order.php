<?php
require_once ('../database/db.php');

// Check if the appointment ID is provided
if (!isset ($_POST['id'])) {
    header("HTTP/1.1 400 Bad Request");
    exit();
}

$orderId = $_POST['id'];


// Cancel the appointment
$stmt = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE OrderId = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();

// Check if the appointment was successfully canceled
if ($stmt->affected_rows === 0) {
    // Failed to cancel the appointment
    header("HTTP/1.1 500 Internal Server Error");
    exit();
}

// Appointment canceled successfully
header("HTTP/1.1 200 OK");
echo json_encode(["message" => "Order canceled"]);
