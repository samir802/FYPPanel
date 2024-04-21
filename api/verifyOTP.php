<?php
session_start();

$response = array(); // Initialize response array

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['otp']) && isset($_POST['email'])) {
    // Check if OTP and email are provided via POST
    $otp_entered = $_POST['otp'];
    $email = $_POST['email'];

    // Include database connection
    include ('../database/db.php');

    // Fetch user ID from the users table based on the provided email
    $fetchUserIdQuery = "SELECT id FROM users WHERE email = ?";
    $stmt_fetch_user_id = $conn->prepare($fetchUserIdQuery);
    $stmt_fetch_user_id->bind_param("s", $email);
    $stmt_fetch_user_id->execute();
    $result_user_id = $stmt_fetch_user_id->get_result();
    $user_id_row = $result_user_id->fetch_assoc();

    if ($user_id_row) {
        // User found in the database
        $user_id = $user_id_row['id'];

        // Fetch OTP from database for the given user ID
        $fetchOtpQuery = "SELECT otp FROM otp WHERE user_id = ?";
        $stmt_fetch_otp = $conn->prepare($fetchOtpQuery);
        $stmt_fetch_otp->bind_param("i", $user_id);
        $stmt_fetch_otp->execute();
        $result_otp = $stmt_fetch_otp->get_result();
        $otp_row = $result_otp->fetch_assoc();

        if ($otp_row) {
            // OTP found in the database
            $otp_database = $otp_row['otp'];

            if ($otp_entered == $otp_database) {
                // Valid OTP
                $response = array(
                    'status' => 'Success',
                    'message' => 'Valid OTP'
                );
            } else {
                // Invalid OTP
                $response = array(
                    'status' => 'Error',
                    'message' => 'Invalid OTP'
                );
            }
        } else {
            // No OTP found for the given user ID
            $response = array(
                'status' => 'Error',
                'message' => 'No OTP found for this user'
            );
        }
    } else {
        // No user found for the provided email
        $response = array(
            'status' => 'Error',
            'message' => 'User not found for this email'
        );
    }
} else {
    // OTP or email not provided
    $response = array(
        'status' => 'Error',
        'message' => 'OTP or email not provided'
    );
}

// Ensure proper encoding
header('Content-Type: application/json');
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit(); // Add this line to ensure script execution stops after sending JSON response
?>