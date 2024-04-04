<?php
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: GET, POST");
// header("Access-Control-Allow-Headers: Content-Type");
require_once ('../database/db.php');
require_once ('../baseLink.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $requestType = $_POST['type']; // Add this line to get the request type

    // Prepare the SQL statement
    $sql = "SELECT * FROM users WHERE email = '$email'";

    // Execute the SQL statement
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userPasswordHash = $row['password'];



        // Verify the password
        if (password_verify($password, $userPasswordHash) && $row['type'] == $requestType) {
            $user_id = $row['id'];
            $token = bin2hex(random_bytes(16)); // Generate a random token

            // Insert the token into the api_tokens table
            $insert_sql = "INSERT INTO api_tokens (user_id, token) VALUES ('$user_id', '$token')";
            $conn->query($insert_sql);
            $result2 = null;
            if ($row['type'] == 'Customer') {
                $user_id = $row['id'];

                // Prepare the SQL statement
                $sql2 = "SELECT * FROM users WHERE id = '$user_id'";
                // Execute the SQL statement
                $result2 = $conn->query($sql2);

                $row2 = $result2->fetch_assoc();

            }

            $response = array(
                'status' => 'success',
                'message' => 'Login successful',
                'token' => $token,
                'user' => $row,
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Invalid credentials or user type'
            );
        }
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Invalid credentials or user type'
        );
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}