<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

// Include database connection
include ('../database/db.php');

if (!isset($_GET['id'])) {
    header("Location: vehicle.php");
    exit();
}

$vehicleId = $_GET['id'];

$deleteSql = "DELETE FROM vehicles WHERE VehicleID = '$vehicleId'";
$conn->query($deleteSql);

header("Location: vehicles.php");
exit();