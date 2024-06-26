<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

require_once ('../database/db.php');
require_once '../baseLink.php';

// Initialize variables
$filterName = '';
$filterEmail = '';

// Check if the filter form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve filter values
    $filterName = $_POST['filter_name'];
    $filterEmail = $_POST['filter_email'];
}

// Define pagination variables
$recordsPerPage = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

// Build the SQL query with filters
$sql = "SELECT company.*,users.name, users.email, users.phone, users.Image FROM company INNER JOIN users ON company.user_id = users.id";

// Add filters to the query if they are provided
if (!empty($filterName) || !empty($filterEmail)) {
    $sql .= " WHERE ";
    $conditions = [];

    if (!empty($filterName)) {
        $conditions[] = "users.name LIKE '%$filterName%'";
    }

    if (!empty($filterEmail)) {
        $conditions[] = "users.email LIKE '%$filterEmail%'";
    }

    $sql .= implode(" AND ", $conditions);
}

// Count total records
$countSql = "SELECT COUNT(*) AS total FROM ($sql) AS total_records";
$countResult = $conn->query($countSql);
$countRow = $countResult->fetch_assoc();
$totalRecords = $countRow['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Add pagination to the query
$sql .= " LIMIT $offset, $recordsPerPage";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>company List</title>
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
                                    <h3 class="card-title">Company List</h3><br>
                                    <a href="add.php" class="btn btn-success">Add New</a>
                                </div>
                                <div class="card-body">
                                    <!-- Filter form -->
                                    <form method="POST" class="mb-3 d-flex">
                                        <div class="row d-flex align-items-end">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="filter_name">Name</label>
                                                    <input type="text" class="form-control" id="filter_name"
                                                        name="filter_name" value="<?php echo $filterName; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="filter_email">Email</label>
                                                    <input type="text" class="form-control" id="filter_email"
                                                        name="filter_email" value="<?php echo $filterEmail; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- company table -->
                                    <table class="table table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Logo</th>
                                                <th>Company Name</th>
                                                <th>Owner Image</th>
                                                <th>Owner Name</th>
                                                <th> Phone Number </th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()) { ?>
                                                <tr>
                                                    <td>
                                                        <?php echo $row['id']; ?>
                                                    </td>
                                                    <td><img src="<?php echo $img_base . $row['Company_Logo']; ?>" alt="Img"
                                                            width="100" height="100">
                                                    </td>

                                                    <td>
                                                        <?php echo $row['Company_Name']; ?>
                                                    </td>

                                                    <td><img src="<?php echo $img_base . $row['Image']; ?>" alt="Img"
                                                            width="100" height="100">
                                                    </td>

                                                    <td>
                                                        <?php echo $row['name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['phone']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['email']; ?>
                                                    </td>
                                                    <td>
                                                        <a href="edit.php?id=<?php echo $row['id']; ?>"
                                                            class="btn btn-sm btn-primary">Edit</a>
                                                        <a href="delete.php?id=<?php echo $row['id']; ?>"
                                                            class="btn btn-sm btn-danger">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <nav aria-label="Page navigation" style="margin-top:10px;">
                                        <ul class="pagination">
                                            <?php if ($page > 1) { ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?php echo ($page - 1); ?>"
                                                        aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?page=<?php echo $i; ?>">
                                                        <?php echo $i; ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                            <?php if ($page < $totalPages) { ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?php echo ($page + 1); ?>"
                                                        aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </nav>
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