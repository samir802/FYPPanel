<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}

// Include database connection
include('../database/db.php');

// Check if company ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: company.php");
    exit();
}

$companyId = $_GET['id'];

// Delete the company and its associated user
$deleteSql = "DELETE company, users FROM company
              INNER JOIN users ON company.user_id = users.id
              WHERE company.id = $companyId";
$conn->query($deleteSql);

header("Location: company.php");
exit();