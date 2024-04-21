<?php
require_once ('../database/db.php');

$userId = $_POST['userId'];
$oldPassword = $_POST['oldPassword'];
$newPassword = $_POST['newPassword'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Check if the user exists and get the user's password
    $select = "SELECT id, password FROM users WHERE id = ?";
    $stmt = $conn->prepare($select);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $id = $user['id'];
        $hashedPassword = $user['password'];

        // Verify old password
        if (password_verify($oldPassword, $hashedPassword)) {
            // Hash the new password
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password
            $update = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("si", $hashedNewPassword, $id);
            $stmt->execute();
            $response = array(
                "status" => "success",
                "message" => "Password updated successfully"
            );
        } else {
            $response = array(
                "status" => "error",
                "message" => "Old password is incorrect"
            );

        }
    } else {
        $response = array(
            "status" => "error",
            "message" => "User not found"
        );
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}

