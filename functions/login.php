<?php
session_start();
require_once('../database/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement
    $sql = "SELECT * FROM users WHERE email = '$email'";

    // Execute the SQL statement
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userPasswordHash = $row['password'];

        // Verify the password
        if (password_verify($password, $userPasswordHash)) {
            $_SESSION['type'] = $row['type'];
            if ($_SESSION['type'] == 'Admin' || $_SESSION['type'] == 'Company') {
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];

                header("Location: ../dashboard.php");
                exit();

            } else {
                echo '<div class="alert alert-info"> Invalid Credentials </div>';
            }
        } else {
            echo '<div class="alert alert-info"> User not found! </div>';
        }
    }

    header("Location: ../index.php");
    exit();
}