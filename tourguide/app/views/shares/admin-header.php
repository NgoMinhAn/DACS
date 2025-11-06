<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' | ' . SITE_NAME : 'Admin Dashboard | ' . SITE_NAME; ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="<?php echo url('public/css/admin.css'); ?>">
    
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <!-- Top Navigation -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand -->
        <a class="navbar-brand ps-3" href="<?php echo url('admin/dashboard'); ?>">
            <img src="<?php echo url('public/img/logo.png'); ?>" alt="Logo" style="height: 40px; width: auto;">
        </a>
        
        <!-- Sidebar Toggle -->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Navbar -->
        <ul class="navbar-nav ms-auto me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="<?php echo url(); ?>"><i class="fas fa-home me-2"></i>View Site</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="<?php echo url('account/logout'); ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Layout Sidenav -->
    <div id="layoutSidenav">
        <!-- Sidebar Navigation -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="<?php echo url('admin/dashboard'); ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        
                        <div class="sb-sidenav-menu-heading">Management</div>
                        <a class="nav-link" href="<?php echo url('admin/users'); ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Users
                        </a>
                        <a class="nav-link" href="<?php echo url('admin/guides'); ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-tie"></i></div>
                            Guides
                        </a>
                        <a class="nav-link" href="<?php echo url('admin/guideApplications'); ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                            Guide Applications
                        </a>
                        <a class="nav-link" href="<?php echo url('admin/categories'); ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                            Categories
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Admin'; ?>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main>
                <!-- Flash Messages -->
                <?php flash('admin_message'); ?>
                <?php flash('success_message'); ?>

