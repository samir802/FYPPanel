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

    // Delete the order from the database
    $sql = "DELETE FROM order WHERE id = '$orderId'";
    $conn->query($sql);

    // Redirect back to the order page
    header("Location: orders.php");
    exit();
} else {
    // If the order ID is not provided, redirect back to the order page
    header("Location: order.php");
    exit();
}
