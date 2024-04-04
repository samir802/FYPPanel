<?php
include ('../database/db.php');
include ('../baseLink.php');

if (isset ($_GET['value'])) {
    $searchValue = '%' . $_GET['value'] . '%'; // Adding wildcard characters to enable partial matching

    $sql = "SELECT 
        v.VehicleID,
        v.Vehicle_Info,
        v.VehicleBrand,
        v.Capacity,
        v.Engine_capacity,
        v.Fuel_consumption,
        v.Driving_method,
        v.FuelType,
        v.Price,
        v.Vehicle_Image,
        c.Company_Name,
        u.name,
        u.phone,
        u.address,
        u.email,
        u.Image,
        ROUND(COALESCE(AVG(r.Rating), 5), 1) AS Rating
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
    WHERE
        v.VehicleBrand LIKE ? 
    GROUP BY 
        v.VehicleID,
        v.Vehicle_Info,
        v.VehicleBrand,
        v.Capacity,
        v.Engine_capacity,
        v.Fuel_consumption,
        v.Driving_method,
        v.FuelType,
        v.Price,
        v.Vehicle_Image;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchValue);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $menuItems = [];

        while ($row = $result->fetch_assoc()) {
            $menuItems[] = $row;
        }

        // Return the menu items as JSON
        header("HTTP/1.1 200 OK");
        header("Content-Type: application/json");
        echo json_encode($menuItems);
    } else {
        // No menu items found, return appropriate response
        header("HTTP/1.1 204 No Content");
        exit();
    }
}