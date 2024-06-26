<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

require_once ('../database/db.php');

// Pagination variables
$page = $_GET['page'] ?? 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Filter variables
$orderId = $_GET['order_id'] ?? '';
$rented_Date = $_GET['Rented_date'] ?? '';


$sql = "SELECT 
    orders.OrderId, 
    orders.Rented_date, 
    orders.Return_Date, 
    orders.status, 
    users.name AS user_name
FROM 
    orders
LEFT JOIN 
    users ON orders.user_id = users.id
LEFT JOIN 
    vehicles ON orders.vehicle_id = vehicles.VehicleID
LEFT JOIN 
    company ON vehicles.Company_Id = company.id
WHERE 
    company.id = {$_SESSION['id']}";
if (!empty($orderId)) {
    $sql .= " AND orders.OrderId LIKE '%$orderId%'";
}

if (!empty($rented_Date)) {
    $sql .= " AND orders.Rented_date LIKE '%$rented_Date%'";
}

$sql .= " ORDER BY orders.OrderId DESC LIMIT $perPage OFFSET $offset";
$result = $conn->query($sql);

$doctorName = "";

// Count the total number of orders for pagination
$countSql = "SELECT COUNT(*) AS total
             FROM orders
             LEFT JOIN users ON orders.user_id = users.id
             WHERE orders.OrderId LIKE '%$orderId%' OR orders.Rented_date LIKE '%$rented_Date%'";
$countResult = $conn->query($countSql);
$countRow = $countResult->fetch_assoc();
$totalorders = $countRow['total'];
$totalPages = ceil($totalorders / $perPage);

function getUserName($userId)
{
    global $conn;

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a row is returned
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['name'];
    } else {
        return "User not found";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            flex: 1;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        .progress-bar {
            border-radius: 5px;
        }
    </style>
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
                                    <h3 class="card-title">Orders</h3>
                                </div>
                                <div class="card-body">
                                    <form class="mb-3" method="GET">
                                        <div class="row align-items-end">
                                            <div class="col-md-4 mb-3 mb-md-0">
                                                <div class="form-group">
                                                    <label for="order_id">Order Id</label>
                                                    <input type="text" class="form-control" id="order_id"
                                                        name="order_id" value="<?php echo $orderId; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3 mb-md-0">
                                                <div class="form-group">
                                                    <label for="Rented_date">Rented Date</label>
                                                    <input type="date" class="form-control" id="Rented_date"
                                                        name="Rented_date" value="<?php echo $rented_Date; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                    <table class="table table-bordered">
                                        <thead class="table table-dark">
                                            <tr>
                                                <th>Order ID</th>
                                                <th>User Name</th>
                                                <th>Rented Date</th>
                                                <th>Returning Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()) { ?>
                                                <tr>
                                                    <td><?php echo $row['OrderId']; ?></td>
                                                    <td><?php echo $row['user_name']; ?></td>
                                                    <td><?php echo $row['Rented_date']; ?></td>
                                                    <td><?php echo $row['Return_Date']; ?></td>
                                                    <td>
                                                        <?php $status = $row['status'];
                                                        $class = ($status == "Pending") ? "bg-info" : (($status == "Completed") ? "bg-success" : "bg-danger");
                                                        echo '<div class="progress"><div class="progress-bar ' . $class . '" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">' . $status . '</div></div>';
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($status == "Pending") { ?>
                                                            <a href="cancel.php?id=<?php echo $row['OrderId']; ?>"
                                                                class="btn btn-danger me-2">Cancel</a>
                                                        <?php } ?>
                                                        <?php if ($status == "Pending") { ?>
                                                            <a href="complete.php?status=Completed&id=<?php echo $row['OrderId']; ?>"
                                                                class="btn btn-success">Complete</a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <!-- Pagination links -->
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-center">
                                            <?php if ($page > 1) { ?>
                                                <li class="page-item"><a class="page-link"
                                                        href="?page=<?php echo ($page - 1); ?>">Previous</a></li>
                                            <?php } ?>
                                            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><a
                                                        class="page-link"
                                                        href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                            <?php } ?>
                                            <?php if ($page < $totalPages) { ?>
                                                <li class="page-item"><a class="page-link"
                                                        href="?page=<?php echo ($page + 1); ?>">Next</a></li>
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
</body>

</html>