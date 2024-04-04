<?php
require_once ('../database/db.php');

// Check if the token is provided
if (!isset($_GET['token'])) {
    header("HTTP/1.1 400 Bad Request");
    exit();
}

$token = $_GET['token'];

// Retrieve the user profile based on the provided token
$sql = "SELECT u.* FROM users u INNER JOIN api_tokens a ON u.id = a.user_id WHERE a.token = '$token'";
$result = $conn->query($sql);

// Check if a row is returned
if ($result && $result->num_rows > 0) {
    $userProfiles = [];
    while ($row = $result->fetch_assoc()) {
        $userProfiles[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'data' => $userProfiles
    ]);
    exit();
} else {
    // Invalid token or user not found
    header("HTTP/1.1 401 Unauthorized");
    exit();
}