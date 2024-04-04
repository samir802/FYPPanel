<?php
require_once '../baseLink.php';
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../dashboard.php");
    exit();
}
include ('../database/db.php');

// Initialize variables
$info = $brand = $capacity = $eCapacity = $fuel = $drivingmethod = $ftype = $price = $type = '';
$companyId = isset($_GET['id']) ? $_GET['id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $info = $_POST['info'];
    $brand = $_POST['brand'];
    $capacity = $_POST['capacity'];
    $eCapacity = $_POST['eCapacity'];
    $fuel = $_POST['fuel'];
    $drivingmethod = $_POST['drivingMethod'];
    $ftype = $_POST['fType'];
    $price = $_POST['price'];
    $type = $_POST['type'];

    // Handle the image upload
    $img = $_FILES['img']['name'];
    $targetDir = "../uploads/";
    $targetFilePath = $targetDir . basename($img);
    move_uploaded_file($_FILES['img']['tmp_name'], $targetFilePath);

    // Check if the company ID exists in the company table
    $checkCompanyQuery = "SELECT id FROM company WHERE id = '$companyId'";
    $companyResult = $conn->query($checkCompanyQuery);
    if ($companyResult->num_rows == 0) {
        // Redirect with error message if company ID is invalid
        $_SESSION['flash_message'] = "Invalid company ID!$companyId ";
        header("Location: add.php");
        exit();
    }

    // Insert data into the vehicles table
    $insertQuery = "INSERT INTO vehicles (Vehicle_Info, VehicleBrand, Capacity, Engine_capacity, Fuel_consumption, 
                    Driving_method, FuelType, Price, Vehicle_Image, Vehicle_type, Company_Id)
                    VALUES ('$info', '$brand', '$capacity', '$eCapacity', '$fuel', '$drivingmethod', 
                    '$ftype', '$price', '$img', '$type', '$companyId')";
    if ($conn->query($insertQuery) === TRUE) {
        // Set success flash message
        $_SESSION['flash_message'] = "Vehicle added successfully!";
        // Redirect to the company list page
        header("Location: vehicles.php");
        exit();
    } else {
        // Redirect with error message if insertion fails
        $_SESSION['flash_message'] = "Error adding vehicle: " . $conn->error;
        header("Location: add.php?id=$companyId");
        exit();
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Company</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Sidebar -->
        <?php include ('../sidebar.php'); ?>

        <!-- Header -->
        <?php include ('../header.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Main content -->
            <section class="content py-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <?php

                            if (isset($_SESSION['flash_message'])) {
                                echo '<div class="alert alert-info">' . $_SESSION['flash_message'] . '</div>';
                                unset($_SESSION['flash_message']);
                            }

                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Add Vehicle

                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="add.php?id=<?php echo $_GET['id']?>"
                                        enctype="multipart/form-data">


                                        <div class="form-group">
                                            <label for="info">Vehicle Information</label>
                                            <input type="text" class="form-control" id="info" name="info" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="brand">Brand</label>
                                            <input type="text" class="form-control" id="brand" name="brand" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="capacity">Seat Capacity</label>
                                            <input type="number" class="form-control" id="capacity" name="capacity"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="eCapacity">Engine Capacity(cc)</label>
                                            <input type="number" class="form-control" id="eCapacity" name="eCapacity"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="fuel">Fuel Consumption(kmph)</label>
                                            <input type="fuel" class="form-control" id="fuel" name="fuel" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="drivingMethod">Driving method</label>
                                            <select class="form-control" id="drivingMethod" name="drivingMethod"
                                                required>
                                                <option value="">Select Driving method</option>
                                                <option value="Automatic">Automatic</option>
                                                <option value="Manual">Manual</option>
                                                <option value="Semi-Automatic">Semi-Automatic</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="fType">Fuel Type</label>
                                            <input type="fType" class="form-control" id="fType" name="fType" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="price">Price(per day)</label>
                                            <input type="price" class="form-control" id="price" name="price" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="type">Vehicle Type</label>
                                            <select class="form-control" id="type" name="type" required>
                                                <option value="">Select Vehicle Type</option>
                                                <option value="Jeep">Jeep</option>
                                                <option value="Car">Car</option>
                                                <option value="Motorcycle">Motorcycle/Scooter</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label for="img">Image</label>
                                            <input type="file" class="form-control" id="img" name="img" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add Company</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
</body>

</html>