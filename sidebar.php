<?php
require_once 'baseLink.php';
?>
<style>
    .nav-item.active>.nav-link {
        background-color: #007bff;
        /* Change this to the desired active link color */
        color: #fff;
        /* Change this to the desired text color for active link */
    }
</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <h3>RideNepal</h3>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?php echo $base; ?>/dashboard.php"
                        class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <?php if ($_SESSION['id'] == 1) { ?>
                    <li class="nav-item">
                        <a href="<?php echo $base; ?>/users/users.php"
                            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo $base; ?>/company/company.php"
                            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'company.php' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-user-alt"></i>
                            <p>
                                Company
                            </p>
                        </a>
                    </li>
                <?php } else { ?>

                    <li class="nav-item">
                        <a href="<?php echo $base; ?>/vehicles/vehicles.php"
                            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'vehicles.php' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-shuttle-van"></i>
                            <p>
                                Vehicles
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo $base; ?>/orders/orders.php"
                            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                Orders
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo $base; ?>/driver/driver.php"
                            class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'driver.php' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Driver
                            </p>
                        </a>
                    </li>
                <?php } ?>

                <li class="nav-item">
                    <a href="<?php echo $base; ?>/functions/logout.php" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt" style="color: #ff0000;"></i>
                        <p style="color:#ff0000;">
                            Logout
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>