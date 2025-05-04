<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' | ' . SITE_NAME : SITE_NAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo url('public/css/style.css'); ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo url(); ?>"><?php echo SITE_NAME; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url(); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('tourGuide/browse'); ?>">Find a Guide</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Guide Categories
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
                        <a class="nav-link" href="<?php echo url('about'); ?>">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('contact'); ?>">Contact</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if ($_SESSION['user_type'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="<?php echo url('admin/dashboard'); ?>">Admin Dashboard</a></li>
                                <?php elseif ($_SESSION['user_type'] === 'guide'): ?>
                                    <li><a class="dropdown-item" href="<?php echo url('guide/dashboard'); ?>">Guide Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?php echo url('tourGuide/profile/' . $_SESSION['user_id']); ?>">View My Public Profile</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="<?php echo url('user/dashboard'); ?>">My Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?php echo url('user/bookings'); ?>">My Bookings</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?php echo url('account/settings'); ?>">Account Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo url('account/logout'); ?>">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo url('account/login'); ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo url('account/register'); ?>">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link highlight" href="<?php echo url('account/register/guide'); ?>">Become a Guide</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php flash('success_message'); ?>
    
    <!-- Main Content Container -->
    <div class="container my-4"><?php // Main content will be here, closed in footer ?></body>
</html>
