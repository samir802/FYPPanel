<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

// Include database connection
include ('../database/db.php');
include ('../baseLink.php');

// Check if doctor ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: company.php");
    exit();
}

$companyId = $_GET['id'];

// Get doctor details from the database
$sql = "SELECT * FROM company WHERE id = $companyId";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    header("Location: company.php");
    exit();
}

$company = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Handle image upload
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $imgTmpName = $_FILES['img']['name'];
        $imagePath = "../uploads/" . $image;
        $targetFilePath1 = $imagePath . basename($imgTmpName);
        // Move uploaded image to the uploads directory
        move_uploaded_file($_FILES['img']['name'], $targetFilePath1);


        $updateSql = "UPDATE company SET Company_Logo = '$imgTmpName' WHERE id = $companyId";
        $conn->query($updateSql);

    }
    header("Location: company.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Company</title>
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
                                    <h3 class="card-title">Edit Company</h3>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="edit.php?id=<?php echo $companyId; ?>"
                                        enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="img">Image</label><br>
                                            <img src="<?php echo $img_base . $company['Company_Logo'] ?>" alt="Company"
                                                width="100" height="100"><br>
                                            <input type="file" class="form-control" id="img" name="img">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Company</button>
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