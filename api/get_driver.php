<?php
require_once ('../database/db.php');

$type = $_GET['VehicleType'];
$cid = $_GET['CompanyId'];

$sql = "SELECT * 
        FROM driver 
        WHERE Vehicle_type = '$type' AND Company_Id = $cid
        ORDER BY RAND() 
        LIMIT 1";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $driver = [];
    while ($row = $result->fetch_assoc()) {
        $driver[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'data' => $driver
    ]);
    exit();
} else {
    // No driver found for the specified company and vehicle type
    header("HTTP/1.1 404 Not Found");
    exit();
}