<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

require_once ('../database/db.php');

// Check if the order ID is provided
if (isset($_GET['id']) && isset($_GET['status'])) {
    $orderId = $_GET['id'];
    $status = $_GET['status'];

    // Update the appointment status to "cancelled"
    $sql = "UPDATE orders SET status = '$status' WHERE OrderId = '$orderId'";
    $conn->query($sql);

    // Redirect back to the appointments page
    header("Location: orders.php");
    exit();
} else {
    // If the appointment ID is not provided, redirect back to the appointments page
    header("Location: orders.php");
    exit();
}
