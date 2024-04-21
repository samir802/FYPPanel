<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
include ('../database/db.php');
include ('../baseLink.php');

if (!isset($_GET['id'])) {
    header("Location: vehicles.php");
    exit();
}

$vehiclesId = $_GET['id'];

// Get vehicles details from the database
$sql = "SELECT * FROM vehicles WHERE VehicleID = $vehiclesId";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    header("Location: vehicles.php");
    exit();
}

$vehicles = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $info = $_POST['info'];
    $brand = $_POST['brand'];
    $capacity = $_POST['capacity'];
    $engine = $_POST['engine'];
    $fuelC = $_POST['fuelC'];
    $driving = $_POST['driving'];
    $fuel = $_POST['fuel'];
    $price = $_POST['price'];
    $type = $_POST['type'];

    // Check if an image is selected
    if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $imgTmpName = $_FILES['img']['name']; // Use 'tmp_name' instead of 'name'
        $imagePath = "../uploads/" . $imgTmpName; // Use 'name' here for the name of the file
        // Move uploaded image to the uploads directory
        move_uploaded_file($_FILES['img']['tmp_name'], $imagePath); // Use the correct variable $imgTmpName here
        $updateSql = "UPDATE vehicles SET Vehicle_Image='$imgTmpName' WHERE VehicleID=$vehiclesId";
        $conn->query($updateSql);
    }

    // Update other fields in the database
    $updateSql = "UPDATE vehicles SET Vehicle_Info='$info', VehicleBrand='$brand', Capacity='$capacity', Engine_capacity='$engine', Fuel_consumption='$fuelC', Driving_method='$driving', FuelType='$fuel', Price='$price', Vehicle_type='$type' WHERE VehicleID=$vehiclesId";
    $conn->query($updateSql);

    header("Location: vehicles.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit vehicles</title>
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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Edit vehicles</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="edit.php?id=<?php echo $vehiclesId; ?>"
                                        enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="img">Image</label><br>
                                            <img src="<?php echo $img_base . $vehicles['Vehicle_Image'] ?>" alt="Driver"
                                                width="100" height="100"><br>
                                            <input type="file" class="form-control" id="img" name="img">
                                        </div>
                                        <div class="form-group">
                                            <label for="info">Info</label><br>
                                            <input type="text" class="form-control" id="info" name="info"
                                                value="<?php echo $vehicles['Vehicle_Info'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="brand">Brand</label><br>
                                            <input type="text" class="form-control" id="brand" name="brand"
                                                value="<?php echo $vehicles['VehicleBrand'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="capacity">
                                                Capacity
                                            </label><br>
                                            <input type="number" class="form-control" id="capacity" name="capacity"
                                                value="<?php echo $vehicles['Capacity'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="engine">
                                                Engine Capacity(CC)
                                            </label><br>
                                            <input type="number" class="form-control" id="engine" name="engine"
                                                value="<?php echo $vehicles['Engine_capacity'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="fuelC">Fuel Consumption</label><br>
                                            <input type="number" class="form-control" id="fuelC" name="fuelC"
                                                value="<?php echo $vehicles['Fuel_consumption'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="driving">Type</label>
                                            <select class="form-control" id="driving" name="driving" required>
                                                <option value="Manual" <?php if ($vehicles['Driving_method'] === 'Manual')
                                                    echo 'selected'; ?>>Manual
                                                </option>
                                                <option value="Automatic" <?php if ($vehicles['Driving_method'] === 'Automatic')
                                                    echo 'selected'; ?>>Automatic
                                                </option>
                                                <option value="Semi-Automatic" <?php if ($vehicles['Driving_method'] === 'Semi-Automatic')
                                                    echo 'selected'; ?>>Semi-Automatic
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="fuel">Fuel Type</label><br>
                                            <input type="text" class="form-control" id="fuel" name="fuel"
                                                value="<?php echo $vehicles['FuelType'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="price">Price</label><br>
                                            <input type="number" class="form-control" id="price" name="price"
                                                value="<?php echo $vehicles['Price'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <select class="form-control" id="type" name="type" required>
                                                <option value="Jeep" <?php if ($vehicles['Vehicle_type'] === 'Jeep')
                                                    echo 'selected'; ?>>Jeep
                                                </option>
                                                <option value="Car" <?php if ($vehicles['Vehicle_type'] === 'Car')
                                                    echo 'selected'; ?>>Car
                                                </option>
                                                <option value="Motorcycle" <?php if ($vehicles['Vehicle_type'] === 'Motorcycle')
                                                    echo 'selected'; ?>>Motorcycle/Scooter
                                                </option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update vehicles</button>
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