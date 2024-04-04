<?php
require_once ('../database/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Comment = $_POST['Comment'];
    $Rating = $_POST['Rating'];
    $user_ID = $_POST['user_ID'];
    $Order_ID = $_POST['Order_ID'];

    // Prepare the SQL statement to insert the user into the database
    $insertSql = "INSERT INTO rating (Comment, Rating,user_ID, Order_ID) VALUES ('$Comment','$Rating','$user_ID','$Order_ID')";

    if ($conn->query($insertSql) === TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Review added successfully'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Error adding review: ' . $conn->error
        );
    }

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
