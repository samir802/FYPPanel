<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

require_once('../database/db.php');

// Check if the order ID is provided
if (isset($_GET['id'])) {
    $orderId = $_GET['id'];

    // Update the appointment status to "cancelled"
    $sql = "UPDATE orders SET status = 'cancelled' WHERE id = '$orderId'";
    $conn->query($sql);

    // Redirect back to the appointments page
    header("Location: orders.php");
    exit();
} else {
    // If the appointment ID is not provided, redirect back to the appointments page
    header("Location: orders.php");
    exit();
}
