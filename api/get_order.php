<?php

// Include database connection
include('../database/db.php');
include('../baseLink.php');



// Get the token from the request headers or query parameters
$token = $_GET['token'] ?? '';


// Verify the token and retrieve the user ID
$user_id = verifyToken($token);


if ($user_id) {
    // Token is valid, get order based on user ID and type


    // $order = getorder($user_id);

    if ($order) {
        // order found
        // You can encode the order as JSON and send the response
        $response = json_encode([
            'data' => $order
        ]);
        header('Content-Type: application/json');
        echo $response;
    } else {
        // No order found
        $response = json_encode(['message' => 'No order found']);
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

        // Close the database connection
        // $conn->close();

        return $user_id;
    } else {
        // Token is invalid or not found
        // Close the database connection
        $conn->close();

        return null;
    }
}

// Function to get order based on user ID and user type
// function getorder($user_id)
// {
//     global $conn;

//     // Sanitize the user ID to prevent SQL injection
//     $user_id = $conn->real_escape_string($user_id);

//     // Check the user's type (assuming you have a 'type' column in the users table)
//     $sql = "SELECT type FROM users WHERE id = '$user_id'";
//     $result = $conn->query($sql);

//     if ($result && $result->num_rows > 0) {
//         $row = $result->fetch_assoc();
//         $user_type = $row['type'];

//         // Retrieve order based on user type
//         if ($user_type === 'Customer') {
//             // For vehicle, find the vehicle row based on user ID
//             $sql = "SELECT id FROM doctors WHERE user_id = '$user_id'";
//             $result = $conn->query($sql);
//             if ($result && $result->num_rows > 0) {
//                 $row = $result->fetch_assoc();
//                 $doctor_id = $row['id'];
//                 // Get order for the specific doctor
//                 // $sql = "SELECT *
//                 // FROM doctors d
//                 // JOIN users u ON d.user_id = u.id
//                 // JOIN order a ON d.id = a.doctor_id
//                 // WHERE d.id = '$doctor_id'

//                 $sql = "SELECT * FROM order where doctor_id = '$doctor_id'";
//                 $result = $conn->query($sql);

//                 if ($result && $result->num_rows > 0) {
//                     $order = [];
//                     while ($row = $result->fetch_assoc()) {
//                         $user_id = $row['user_id'];
//                         $s = "SELECT * from users WHERE id = '$user_id'";
//                         $r = $conn->query($s);
//                         while ($userD = $r->fetch_assoc()) {
//                             $row['customer'] = $userD;
//                         }
//                         $order[] = $row;
//                     }

//                     return $order;
//                 }
//             }
//         } else {
//             // For customers or other user types, get order based on user ID
//             $sql = "SELECT *
//             FROM doctors d
//             JOIN users u ON d.user_id = u.id
//             JOIN order a ON d.id = a.doctor_id
//             WHERE a.user_id = '$user_id'
//             ";
//             $result = $conn->query($sql);

//             if ($result && $result->num_rows > 0) {
//                 $order = [];
//                 while ($row = $result->fetch_assoc()) {
//                     $order[] = $row;
//                 }

//                 return $order;
//             }
//         }
//     }

//     // No order found or user not found
//     return null;
// }

// Close the database connection
$conn->close();
