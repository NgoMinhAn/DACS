<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' | ' . SITE_NAME : SITE_NAME; ?></title>

    <!-- Favicon / Icon -->
    <link rel="icon" type="image/png" href="<?php echo url('public/img/icon.png'); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo url('public/img/icon.png'); ?>">
    <!-- Nếu bạn có file favicon.ico, uncomment dòng này -->
    <!-- <link rel="icon" type="image/x-icon" href="<?php echo url('public/img/favicon.ico'); ?>"> -->

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo url('public/css/style.css'); ?>">



</head>

<body class="d-flex flex-column min-vh-100">
    <?php
    // Xác định trang hiện tại
    $current_uri = $_SERVER['REQUEST_URI'];
    $base = dirname($_SERVER['SCRIPT_NAME']);
    if (strpos($current_uri, $base) === 0) {
        $current_uri = substr($current_uri, strlen($base));
    }
    // Remove query string
    if (($pos = strpos($current_uri, '?')) !== false) {
        $current_uri = substr($current_uri, 0, $pos);
    }
    $current_uri = rtrim($current_uri, '/');
    $current_path = trim($current_uri, '/');
    
    // Xác định trang đang active (kiểm tra theo thứ tự ưu tiên)
    $is_home = empty($current_path) || $current_path === '';
    $is_about = strpos($current_path, 'about') === 0;
    $is_destinations = strpos($current_path, 'tourGuide/browse') === 0;
    $is_tours = strpos($current_path, 'tourGuide/category') === 0;
    $is_contact = strpos($current_path, 'contact') === 0;
    ?>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top" style="background-color: #1a1a1a;">
        <div class="container">
            <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="<?php echo url(); ?>">
                <img src="<?php echo url('public/img/logo.png'); ?>" alt="Logo" class="logo-img" style="height: 50px; max-width: 150px; margin-right: 12px; object-fit: contain;">
                <span class="logo-text"><?php echo SITE_NAME; ?></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link rounded px-3 <?php echo $is_home ? 'active' : 'text-white'; ?>" href="<?php echo url(); ?>">
                            Trang Chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded px-3 <?php echo $is_about ? 'active' : 'text-white'; ?>" href="<?php echo url('about'); ?>">
                            Giới Thiệu
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link rounded px-3 dropdown-toggle <?php echo $is_tours ? 'active' : 'text-white'; ?>" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            Tours
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo url('tourGuide/category/city'); ?>">City Tours</a></li>
                            <li><a class="dropdown-item" href="<?php echo url('tourGuide/category/adventure'); ?>">Adventure Tours</a></li>
                            <li><a class="dropdown-item" href="<?php echo url('tourGuide/category/cultural'); ?>">Cultural Tours</a></li>
                            <li><a class="dropdown-item" href="<?php echo url('tourGuide/category/food'); ?>">Food Tours</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo url('tourGuide/category/categories'); ?>">All Categories</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded px-3 <?php echo $is_destinations ? 'active' : 'text-white'; ?>" href="<?php echo url('tourGuide/browse'); ?>">
                            Tour Guides
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded px-3 <?php echo $is_contact ? 'active' : 'text-white'; ?>" href="<?php echo url('contact'); ?>">
                            Liên Hệ
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0 d-flex align-items-center">
                    <li class="nav-item me-3">
                        <a class="nav-link text-white" href="#" title="Tìm kiếm">
                            <i class="fas fa-search"></i>
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="btn btn-warning text-white fw-bold px-4" href="<?php echo url('tourGuide/browse'); ?>" style="background-color: #FF9800; border: none;">
                            Book Now <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link text-white" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if ($_SESSION['user_type'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="<?php echo url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                                <?php elseif ($_SESSION['user_type'] === 'guide'): ?>
                                    <li><a class="dropdown-item" href="<?php echo url('guide/dashboard'); ?>">Guide Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?php echo url('tourGuide/profile/' . $_SESSION['user_id']); ?>">View My Public Profile</a></li>
                                    <li><a class="dropdown-item" href="<?php echo url('guide/account-settings'); ?>">Account Settings</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="<?php echo url('user/bookings'); ?>">My Bookings</a></li>
                                    <li><a class="dropdown-item" href="<?php echo url('account/settings'); ?>">Account Settings</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo url('account/logout'); ?>">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle" style="font-size: 1.5rem;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo url('account/login'); ?>">Login</a></li>
                                <li><a class="dropdown-item" href="<?php echo url('account/register'); ?>">Register</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php flash('success_message'); ?>

    <!-- Main Content Container -->
    <div class="container my-4 flex-grow-1"><?php // Main content will be here, closed in footer ?>
</body>

</html>