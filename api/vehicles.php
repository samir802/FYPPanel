<?php
include('../database/db.php');
include('../baseLink.php');

$query = "SELECT * from vehicles";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $vehicles = array();
    while ($row = $result->fetch_assoc()) {
        $vehicle = array(
            'VehicleID' => $row['VehicleID'],
            'VehicleBrand' => $row['VehicleBrand'],
            'Capacity' => $row['Capacity'],
            'FuelType' => $row['FuelType'],
            'Price' => $row['Price'],
        );
        $vehicles[] = $vehicle;
    }
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'data' => $vehicles
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'No Vehicles found.'));
}

// Close the database connection
$conn->close();
