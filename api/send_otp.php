<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

include ('../database/db.php');
include ('../baseLink.php');

session_start();

$response = array(); // Initialize response array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is provided
    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        // Check if the email exists in the users table
        $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
        $stmt_check = $conn->prepare($checkEmailQuery);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        $user = $result->fetch_assoc();

        if ($user) { // Check if user exists
            // Generate OTP
            $otp = rand(100000, 999999); // Generate a 6-digit OTP

            // Check if the user already has an OTP in the database
            $checkOTPQuery = "SELECT user_id FROM otp WHERE user_id = ?";
            $stmt_check_otp = $conn->prepare($checkOTPQuery);
            $stmt_check_otp->bind_param("i", $user['id']);
            $stmt_check_otp->execute();
            $result_otp = $stmt_check_otp->get_result();
            $existing_otp = $result_otp->fetch_assoc();

            if ($existing_otp) {
                // If OTP exists, update the existing OTP
                $updateQuery = "UPDATE otp SET otp = ? WHERE user_id = ?";
                $stmt_update = $conn->prepare($updateQuery);
                $stmt_update->bind_param("ii", $otp, $user['id']);
                $stmt_update->execute();
            } else {
                // If OTP doesn't exist, insert a new OTP
                $insertQuery = "INSERT INTO otp (otp, user_id) VALUES (?, ?)";
                $stmt_insert = $conn->prepare($insertQuery);
                $stmt_insert->bind_param("ii", $otp, $user['id']);
                $stmt_insert->execute();
            }

            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;
            $_SESSION['id'] = $user['id'];

            // Send Email
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth = true;                                   //Enable SMTP authentication
                $mail->Username = 'shresthasammir@gmail.com';                     //SMTP username
                $mail->Password = 'pyuz usbm ryui zvwg';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('shresthasammir@gmail.com', 'Samir');
                $mail->addAddress($email);     //Add a recipient

                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'OTP for Password Change';
                $mail->Body = "Your OTP is: $otp";

                $mail->send();

                $response = array(
                    'status' => 'success',
                    'message' => 'Message has been sent',
                );
            } catch (Exception $e) {
                $response = array(
                    'status' => 'error',
                    'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"
                );
            }
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Email not found in the database'
            );
        }
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Email not provided'
        );
    }
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Invalid request method'
    );
}

header('Content-Type: application/json');
echo json_encode($response);
exit(); // Add this line to ensure script execution stops after sending JSON response
?>