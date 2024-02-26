<?php
require_once 'baseLink.php';
?>
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
                    <a href="<?php echo $base; ?>/dashboard.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>/users/users.php" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>/company/company.php" class="nav-link">
                        <i class="nav-icon fas fa-user-alt"></i>
                        <p>
                            Company
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>/vehicles/vehicles.php" class="nav-link">
                        <i class="nav-icon fas fa-shuttle-van"></i>
                        <p>
                            Vehicles
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $base; ?>/orders/orders.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Orders
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./functions/logout.php" class="nav-link">
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