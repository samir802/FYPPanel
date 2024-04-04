<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
require_once ('../database/db.php');

// Check if the user ID is provided
if (!isset($_GET['id'])) {
    header("Location: ../dashboard.php");
    exit();
}

// Retrieve the user ID from the query parameter
$id = $_GET['id'];

// First, delete related records from orders table
$sql_delete_orders = "DELETE FROM orders WHERE user_id = '$id'";
if (!$conn->query($sql_delete_orders)) {
    // Handle error if deletion of related records fails
    echo "Error deleting related orders: " . $conn->error;
    exit();
}

// Second, delete related records from api_tokens table
$sql_delete_tokens = "DELETE FROM api_tokens WHERE user_id = '$id'";
if (!$conn->query($sql_delete_tokens)) {
    // Handle error if deletion of related records fails
    echo "Error deleting related records: " . $conn->error;
    exit();
}

// Then, delete the user record from the database
$sql_delete_user = "DELETE FROM users WHERE id = '$id'";
if (!$conn->query($sql_delete_user)) {
    // Handle error if deletion of user record fails
    echo "Error deleting user record: " . $conn->error;
    exit();
}

// Redirect to the dashboard page
header("Location: users.php");
exit();