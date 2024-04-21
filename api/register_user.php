<?php
require_once ('../database/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $type = isset($_POST['type']) ? $_POST['type'] : 'Customer';

    // Check if the email already exists in the database
    $checkEmailSql = "SELECT * FROM users WHERE email = '$email'";
    $emailResult = $conn->query($checkEmailSql);

    if ($emailResult->num_rows > 0) {
        $response = array(
            'status' => 'error',
            'message' => 'Email already exists'
        );
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $image = $_FILES['image']['name'];
        $imagePath = "../uploads/" . $image;
        // move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $insertSql = "INSERT INTO users (name, phone, address, email, password, type, Image) VALUES ('$name','$phone','$address','$email', '$hashedPassword', '$type', '$image')";

            if ($conn->query($insertSql) === TRUE) {
                $response = array(
                    'status' => 'success',
                    'message' => 'User registered successfully'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Error registering user: ' . $conn->error
                );
            }
        } else {
            // File upload failed
            $response = array(
                'status' => 'error',
                'message' => 'Error uploading image'
            );
        }
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}