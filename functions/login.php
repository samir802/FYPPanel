<?php
session_start();
require_once ('../database/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT 
    u.id AS UserID,
    u.name AS UserName,
    u.phone AS UserPhone,
    u.address AS UserAddress,
    u.email AS UserEmail,
    u.password AS UserPassword,
    u.Image AS UserImage,
    u.type AS UserType,
    c.id AS CompanyID,
    c.Company_Name AS CompanyName,
    c.user_id AS CompanyUserID
FROM 
    users u
LEFT JOIN 
    company c ON u.id = c.user_id
WHERE
    u.email = '$email'";

    // Execute the SQL statement
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userPasswordHash = $row['UserPassword']; // Fixed: Use the correct column name

        // Verify the password
        if (password_verify($password, $userPasswordHash)) {
            $_SESSION['type'] = $row['UserType'];
            if ($_SESSION['type'] == 'Admin') {
                $_SESSION['id'] = $row['UserID'];
                $_SESSION['name'] = $row['UserName'];

                header("Location: ../dashboard.php");
                exit(); // Ensure no further output after redirect
            } elseif ($_SESSION['type'] == 'Company') {
                $_SESSION['id'] = $row['CompanyID'];
                $_SESSION['name'] = $row['CompanyName'];

                header("Location: ../dashboard.php");
                exit(); // Ensure no further output after redirect
            }
        }
    }

    // If user not found or password incorrect, set error message in session and redirect to index.php
    $_SESSION['login_error'] = "Invalid email or password.";
    header("Location: ../index.php");
    exit(); // Ensure no further output after redirect
}
