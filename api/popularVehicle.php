<?php
include ('../database/db.php');
include ('../baseLink.php');

$query = "SELECT 
     v.VehicleID,
    v.Vehicle_Info,
    v.VehicleBrand,
    v.Capacity,
    v.Engine_capacity,
    v.Fuel_consumption,
    v.Driving_method,
    v.FuelType,
    v.Vehicle_Type,
    v.Price,
    v.Vehicle_Image,
    c.id as Company_Id,
    c.Company_Name,
    c.Company_Logo,
    u.*,
    ROUND(AVG(r.Rating), 1) AS AvgRating
FROM 
    vehicles v
INNER JOIN 
    orders o ON v.VehicleID = o.vehicle_id
INNER JOIN 
    rating r ON o.OrderId = r.Order_ID
INNER JOIN 
    company c ON v.Company_Id = c.id
INNER JOIN 
    users u ON c.user_id = u.id
GROUP BY 
    v.VehicleID
HAVING 
    AVG(r.Rating) >= 3;


";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $vehicles = array();
    while ($row = $result->fetch_assoc()) {
        $vehicle = array(
            'VehicleID' => $row['VehicleID'],
            'Vehicle_Info' => $row['Vehicle_Info'],
            'VehicleBrand' => $row['VehicleBrand'],
            'Capacity' => $row['Capacity'],
            'Engine_capacity' => $row['Engine_capacity'],
            'Fuel_consumption' => $row['Fuel_consumption'],
            'Driving_method' => $row['Driving_method'],
            'FuelType' => $row['FuelType'],
            'Price' => $row['Price'],
            'Vehicle_Image' => $row['Vehicle_Image'],
            'Vehicle_Type' => $row['Vehicle_Type'],
            'Company_Id' => $row['Company_Id'],
            'Company_Name' => $row['Company_Name'],
            'Company_Logo' => $row['Company_Logo'],
            'name' => $row['name'],
            'phone' => $row['phone'],
            'address' => $row['address'],
            'email' => $row['email'],
            'Image' => $row['Image'],
            'Rating' => $row['AvgRating'],

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
