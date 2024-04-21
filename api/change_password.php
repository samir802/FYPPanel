<?php

include ('../database/db.php');
include ('../baseLink.php');

$response = array();

// User email is set, proceed with password change
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if password is provided
    if (!isset($_POST['password'])) {
        // Handle the case where password is not provided
        $response['error'] = "Password not provided.";
    } else {
        // Get password from the form
        $password = $_POST['password'];

        // Validate the password (perform any necessary validation here)

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Get user ID from session email
        $email = $_POST['email'];

        // Prepare and execute the SQL statement to retrieve user ID based on email
        $getUserIDSQL = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($getUserIDSQL);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $id = $row['id'];

            // Prepare and execute the SQL statement to update password
            $changePasswordSQL = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($changePasswordSQL);
            $stmt->bind_param("si", $hashedPassword, $id);

            if ($stmt->execute()) {
                // Password updated successfully
                $response['success'] = true;
                $response['message'] = "Password updated successfully.";
            } else {
                // Handle the case where an error occurred during execution
                $response['error'] = "Error updating password: " . $stmt->error;
            }
        } else {
            // Handle the case where user with the given email is not found
            $response['error'] = "User with the given email not found.";
        }

        // Close the statement
        $stmt->close();
    }
} else {
    // Handle the case where the form was not submitted
    $response['error'] = "Form submission required.";
}

// Convert the response array to JSON and output it
header('Content-Type: application/json');
echo json_encode($response);
exit(); // Ensure script execution stops after sending JSON response
