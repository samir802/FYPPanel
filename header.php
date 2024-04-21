<?php
$sqlCompanyImage = "SELECT Company_Logo FROM company WHERE id = {$_SESSION['id']}";
$resultCompanyImage = $conn->query($sqlCompanyImage);
$companyImageURL = null; // Initialize to null
if ($resultCompanyImage && $resultCompanyImage->num_rows > 0) {
    $rowCompanyImage = $resultCompanyImage->fetch_assoc();
    $companyImageURL = $rowCompanyImage['Company_Logo'];
}
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li>
            <img src="../uploads/<?php echo $companyImageURL; ?>" class="Circle" style="
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    margin: auto;
                    ">
        </li>
    </ul>
</nav>