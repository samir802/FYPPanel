<?php
require_once ('../baseLink.php');
include ('../database/db.php');
// session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

// Initialize variables
$name = '';
$address = '';
$phone = '';
$price = '';
$VehicleType = '';
$CompanyId = $_SESSION['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $price = $_POST['price'];
    $VehicleType = $_POST['VehicleType'];

    $sql = "INSERT INTO driver (Driver_Name,Phone,Address,Price,Vehicle_Type,Company_Id) VALUES ('$name','$phone','$address','$price','$VehicleType','$CompanyId')";
    $conn->query($sql);

    // Set success flash message
    $_SESSION['flash_message'] = "Driver added successfully!";

    // Redirect to the company list page
    header("Location: driver.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Driver</title>
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
                                    <h3 class="card-title">Add Driver</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="add.php" enctype="multipart/form-data">

                                        <div class="form-group">
                                            <label for="name">Driver Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone number:</label>
                                            <input type="number" class="form-control" id="phone" name="phone" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <input type="price" class="form-control" id="price" name="price" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="VehicleType">Vehicle Type</label>
                                            <select class="form-control" id="VehicleType" name="VehicleType" required>
                                                <option value="">Select Vehicle Type</option>
                                                <option value="Jeep">Jeep</option>
                                                <option value="Car">Car</option>
                                                <option value="Motorcycle">Motorcycle/Scooter</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add Driver</button>
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