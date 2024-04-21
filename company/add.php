<?php
require_once ('../baseLink.php');

session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
include ('../database/db.php');

// Initialize variables
$name = '';
$email = '';
$phone = '';
$owner = '';

// Check if the email already exists in the users table
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $emailExistsSql = "SELECT id FROM users WHERE email = '$email'";
    $emailExistsResult = $conn->query($emailExistsSql);

    if ($emailExistsResult->num_rows > 0) {
        // Email already exists, set flash message and redirect back with form data
        $_SESSION['flash_message'] = "Email already exists!";
        $_SESSION['form_data'] = $_POST;
        header("Location: add.php");
        exit();
    }

    // Retrieve form data
    $owner = $_POST['owner'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Hash the password
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    // Handle the image upload
    $img = $_FILES['img']['name'];
    $targetDir = "../uploads/";
    $targetFilePath = $targetDir . basename($img);
    move_uploaded_file($_FILES['img']['tmp_name'], $targetFilePath);
    // Handle the image upload
    $img2 = $_FILES['img1']['name'];
    $targetDir1 = "../uploads/";
    $targetFilePath1 = $targetDir1 . basename($img2);
    move_uploaded_file($_FILES['img1']['tmp_name'], $targetFilePath1);

    // Create a new user
    $userSql = "INSERT INTO users (name,phone,address,email, password, image, type) VALUES ('$owner','$phone','$address','$email', '$hashedPassword','$img', 'Company')";
    $conn->query($userSql);

    // Get the user_id of the newly created user
    $user_id = $conn->insert_id;


    // Insert the company into the "company" table
    $name = $_POST['name'];

    $companySql = "INSERT INTO company (Company_Name,user_id,Company_Logo) VALUES ('$name','$user_id','$img2')";
    $conn->query($companySql);

    // Set success flash message
    $_SESSION['flash_message'] = "Company added successfully!";

    // Redirect to the company list page
    header("Location: company.php");
    exit();
}

// Fill form data if redirected back due to existing email
if (isset($_SESSION['form_data'])) {
    $name = $_SESSION['form_data']['name'];
    $email = $_SESSION['form_data']['email'];
    unset($_SESSION['form_data']);
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
                                    <h3 class="card-title">Add Company</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="add.php" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="name">Company Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="owner">Owner Name</label>
                                            <input type="text" class="form-control" id="owner" name="owner" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone Number:</label>
                                            <input type="text" class="form-control" id="phone" name="phone" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label for="img">Image</label>
                                            <input type="file" class="form-control" id="img" name="img" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="img1">Company Logo</label>
                                            <input type="file" class="form-control" id="img1" name="img1" required>
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