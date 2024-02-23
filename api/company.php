<?php
// Include database connection
include('../database/db.php');
include('../global.php');

// Prepare and execute the query to retrieve Company' data
$query = "SELECT company.* users.name, users.email, users.Image
          FROM Company
          INNER JOIN users ON Company.user_id = users.id";

$result = $conn->query($query);

// Check if there are any Company found
if ($result->num_rows > 0) {
    $Company = array();
    while ($row = $result->fetch_assoc()) {
        // Add Company data to the array
        $Company = array(
            'id' => $row['id'],
            'Company_Name' => $row['Company_Name'],
            'name' => $row['name'],
            'email' => $row['email'],
            'address' => $row['address'],
            'phone' => $row['phone'],
            'Image' => $img_base . $row['Image'],
        );
        $Company[] = $Company;
    }

    // Return Company' data as JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'data' => $Company
    ]);
} else {
    // No Company found
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'No Company found.'));
}

// Close the database connection
$conn->close();
