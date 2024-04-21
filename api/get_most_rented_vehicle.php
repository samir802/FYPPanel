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
    u.name,
    u.phone,
    u.address,
    u.email,
    u.Image,
    ROUND(COALESCE(AVG(r.Rating), 5), 1) AS Rating,
    COUNT(o.OrderId) AS RentalCount
FROM 
    vehicles v
LEFT JOIN
    company c ON v.Company_Id = c.id
LEFT JOIN
    users u ON c.user_id = u.id
LEFT JOIN 
    orders o ON v.VehicleID = o.vehicle_id
LEFT JOIN 
    rating r ON o.OrderId = r.Order_ID
GROUP BY 
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
    c.id,
    u.id
ORDER BY
    RentalCount DESC
LIMIT 5;
";


$result = $conn->query($query);
if ($result->num_rows > 0) {
    $mostRentedVehicle = array();
    while ($row = $result->fetch_assoc()) {
        $vehicles = array(
            'VehicleID' => $row['VehicleID'],
            'Vehicle_Info' => $row['Vehicle_Info'],
            'VehicleBrand' => $row['VehicleBrand'],
            'Capacity' => $row['Capacity'],
            'Engine_capacity' => $row['Engine_capacity'],
            'Fuel_consumption' => $row['Fuel_consumption'],
            'Driving_method' => $row['Driving_method'],
            'FuelType' => $row['FuelType'],
            'Vehicle_Type' => $row['Vehicle_Type'],
            'Price' => $row['Price'],
            'Vehicle_Image' => $row['Vehicle_Image'],
            'Company_Id' => $row['Company_Id'],
            'Company_Name' => $row['Company_Name'],
            'Company_Logo' => $row['Company_Logo'],
            'name' => $row['name'],
            'phone' => $row['phone'],
            'address' => $row['address'],
            'email' => $row['email'],
            'Image' => $row['Image'],
            'Rating' => $row['Rating'],
            'RentalCount' => $row['RentalCount'],
        );
        $mostRentedVehicle[] = $vehicles;
    }
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'data' => $mostRentedVehicle
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'No mostRentedVehicle found.'));
}

// Close the database connection
$conn->close();
