<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

// Include database connection
include ('../database/db.php');

if (!isset($_GET['Driver_ID'])) {
    header("Location: driver.php");
    exit();
}

$driverId = $_GET['Driver_ID'];

$deleteSql = "DELETE FROM driver WHERE Driver_Id = '$driverId'";
$conn->query($deleteSql);

header("Location: driver.php");
exit();