<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
include ('../database/db.php');
include ('../baseLink.php');

if (!isset($_GET['Driver_ID'])) {
    header("Location: driver.php");
    exit();
}

$driverId = $_GET['Driver_ID'];

// Get driver details from the database
$sql = "SELECT * FROM driver WHERE Driver_ID = $driverId";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    header("Location: driver.php");
    exit();
}

$driver = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $price = $_POST['price'];
    $type = $_POST['type'];

    // Update the database with the new name
    $updateSql = "UPDATE driver SET Driver_Name = ?, Phone=?, Address=?, Price=?, Vehicle_type=? WHERE Driver_ID = ?";
    $stmt = $conn->prepare($updateSql);

    // Bind parameters
    $stmt->bind_param("sisisi", $name, $phone, $address, $price, $type, $driverId);

    // Execute the update query
    if ($stmt->execute()) {
        // Redirect to the driver list page after successful update
        header("Location: driver.php");
        exit();
    } else {
        // Error occurred during the update
        echo "Error updating driver: " . $stmt->error;
    }

}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Driver</title>
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
                                    <h3 class="card-title">Edit Driver</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="edit.php?Driver_ID=<?php echo $driverId; ?>"
                                        enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="name">Name</label><br>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="<?php echo $driver['Driver_Name'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label><br>
                                            <input type="number" class="form-control" id="phone" name="phone"
                                                value="<?php echo $driver['Phone'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label><br>
                                            <input type="text" class="form-control" id="address" name="address"
                                                value="<?php echo htmlspecialchars($driver['Address']); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="price">Price</label><br>
                                            <input type="number" class="form-control" id="price" name="price"
                                                value="<?php echo $driver['Price'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <select class="form-control" id="type" name="type" required>
                                                <option value="Jeep" <?php if ($driver['Vehicle_type'] === 'Jeep')
                                                    echo 'selected'; ?>>Jeep</option>
                                                <option value="Car" <?php if ($driver['Vehicle_type'] === 'Car')
                                                    echo 'selected'; ?>>Car</option>
                                                <option value="Motorcycle" <?php if ($driver['Vehicle_type'] === 'Motorcycle')
                                                    echo 'selected'; ?>>
                                                    Motorcycle/Scooter</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Driver</button>
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