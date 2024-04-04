<?php

// Include database connection
include ('../database/db.php');
include ('../baseLink.php');

// Get the token from the request headers or query parameters
$token = $_GET['token'] ?? '';

// Verify the token and retrieve the user ID
$user_id = verifyToken($token);

if ($user_id) {
    // Token is valid, get orders along with user and vehicle details based on user ID
    $orders = getOrdersWithDetails($user_id);

    if ($orders) {
        // Orders found
        // You can encode the orders as JSON and send the response
        $response = json_encode(['data' => $orders]);
        header('Content-Type: application/json');
        echo $response;
    } else {
        // No orders found
        $response = json_encode(['message' => 'No orders found']);
        header('Content-Type: application/json');
        echo $response;
    }
} else {
    // Invalid token or token not provided
    $response = json_encode(['message' => 'Invalid token']);
    header('Content-Type: application/json');
    echo $response;
}

// Verify the token and retrieve the user_id
function verifyToken($token)
{
    global $conn;

    // Sanitize the token to prevent SQL injection
    $token = $conn->real_escape_string($token);

    // Query the api_tokens table to check if the token exists
    $sql = "SELECT user_id FROM api_tokens WHERE token = '$token'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Token is valid, retrieve the user_id
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];

        return $user_id;
    } else {
        // Token is invalid or not found
        return null;
    }
}

// Function to get orders along with user and vehicle details based on user ID
function getOrdersWithDetails($user_id)
{
    global $conn;

    // Sanitize the user ID to prevent SQL injection
    $user_id = $conn->real_escape_string($user_id);

    // Query to get orders along with user and vehicle details
    $sql = "SELECT o.*, u.*, v.* 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            JOIN vehicles v ON o.vehicle_id = v.VehicleID 
            WHERE o.user_id = '$user_id'
            ORDER BY o.Rented_date DESC
            ";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    } else {
        // No orders found
        return null;
    }
}

// Close the database connection
$conn->close();
