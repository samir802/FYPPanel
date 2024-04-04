<?php
require_once 'baseLink.php';
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

require_once ('./database/db.php'); // Assuming you have a file to handle database connection

// Fetch total count of users
$sqlUsers = "SELECT COUNT(*) AS count FROM users";
$resultUsers = $conn->query($sqlUsers);
$rowUsers = $resultUsers->fetch_assoc();
$userCount = $rowUsers['count'];

// Fetch total count of companies
$sqlCompanies = "SELECT COUNT(*) AS count FROM company";
$resultCompanies = $conn->query($sqlCompanies);
$rowCompanies = $resultCompanies->fetch_assoc();
$companyCount = $rowCompanies['count'];

// Fetch total count of vehicles
$sqlVehicles = "SELECT COUNT(*) AS count FROM vehicles WHERE company_Id={$_SESSION['id']}";
$resultVehicles = $conn->query($sqlVehicles);
$rowVehicles = $resultVehicles->fetch_assoc();
$vehicleCount = $rowVehicles['count'];

// Fetch total count of orders
$sessionCompanyId = $_SESSION['id'];
$sqlOrders = "SELECT COUNT(*) AS count
                   FROM orders
                   JOIN vehicles ON orders.vehicle_id = vehicles.VehicleID
                   WHERE vehicles.Company_Id = $sessionCompanyId";
$resultOrders = $conn->query($sqlOrders);
$rowOrders = $resultOrders->fetch_assoc();
$orderCount = $rowOrders['count'];

// Fetch count of orders grouped by rented month
$ordersData = array();
if ($_SESSION['id'] != 1) {
    $sqlOrdersByMonth = "SELECT DATE_FORMAT(o.rented_date, '%Y-%m') AS rented_month, COUNT(*) AS count
                   FROM orders o
                   JOIN vehicles v ON o.vehicle_id = v.VehicleID
                   WHERE v.Company_Id = $sessionCompanyId
                   GROUP BY rented_month";
    $resultOrdersByMonth = $conn->query($sqlOrdersByMonth);

    // Initialize array to hold data for the pie chart
    while ($row = $resultOrdersByMonth->fetch_assoc()) {
        $ordersData[] = array("label" => $row['rented_month'], "y" => $row['count']);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <!-- Include necessary CSS and JS files -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <!-- Add the Font Awesome CDN link below -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
        }

        .card-body {
            padding: 20px;
        }

        .container {
            margin-top: 20px;
        }

        .row {
            margin-bottom: 20px;
        }

        h3 {
            font-weight: bold;
            color: #007bff;
        }

        .count-container {
            background-color: #28a745;
            padding: 20px;
            border-radius: 10px;
        }

        .count-container h4 {
            color: white;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Sidebar -->
        <?php include ('sidebar.php'); ?>

        <!-- Header -->
        <?php include ('header.php'); ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Main content -->
            <section class="content py-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <?php if ($_SESSION['id'] == 1) { ?>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-3 count-container" style="margin-right:5px">
                                            <h4>Total Users:
                                                <?php echo $userCount; ?>
                                            </h4>
                                        </div>
                                        <div class="col-md-3 count-container">
                                            <h4>Total Companies:
                                                <?php echo $companyCount; ?>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-3 count-container" style="margin-right:5px">
                                            <h4>Total Vehicles
                                                <div class="col-md-2">
                                                    <?php echo $vehicleCount; ?>
                                                </div>
                                            </h4>
                                        </div>
                                        <div class="col-md-3 count-container">
                                            <h4>Total Orders Placed
                                                <div class="col-md-2">
                                                    <?php echo $orderCount; ?>
                                                </div>
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add canvas for pie chart with smaller dimensions -->
                                <div class="row"
                                    style="background-color:white; max-width:400px; margin-left:50px; border-radius: 20px;">
                                    <div class="col-12 p-3">
                                        <h2>Total Orders by Month</h2>
                                        <div class="divider"></div>
                                        <canvas id="ordersChart" style="max-width: 400px; max-height: 400px;"></canvas>
                                    </div>
                                </div>
                            <?php } ?>
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
    <!-- Include Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
        // JavaScript code to render pie chart
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('ordersChart').getContext('2d');
            var ordersChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode(array_column($ordersData, 'label')); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_column($ordersData, 'y')); ?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)',
                            'rgba(255, 159, 64, 0.5)'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });
        });
    </script>
</body>

</html>