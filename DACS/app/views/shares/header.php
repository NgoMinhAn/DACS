<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Local Guides | Connect with Experts</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background-image: url('/DACS/assests/images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            position: relative;
        }
        
        .hero-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .guide-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .guide-card:hover {
            transform: translateY(-5px);
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .rating {
            color: #ffc107;
        }
        
        .footer {
            background: #343a40;
            color: white;
            padding: 40px 0;
            margin-top: 50px;
        }
        
        .badge-notification {
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(50%, -50%);
        }
    </style>
</head>

<body>
    <?php
    // Start the session if it's not started already
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Function to check if current page matches the given path
    if (!function_exists('isActive')) {
        function isActive($path) {
            $current_page = $_SERVER['REQUEST_URI'];
            
            // Handle base path
            if ($path === '/') {
                return ($current_page === '/DACS/' || $current_page === '/DACS') ? 'active' : '';
            }
            
            // Add /DACS prefix to path for comparison
            $prefixed_path = '/DACS' . $path;
            return strpos($current_page, $prefixed_path) === 0 ? 'active' : '';
        }
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/DACS/">
                <i class="fas fa-user-friends mr-2"></i>LocalGuides
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item <?= isActive('/') ?>">
                        <a class="nav-link" href="/DACS/">Home</a>
                    </li>
                    <li class="nav-item <?= isActive('/guides') ?>">
                        <a class="nav-link" href="/DACS/guides">Find a Guide</a>
                    </li>
                    <li class="nav-item <?= isActive('/about') ?>">
                        <a class="nav-link" href="/DACS/about">About Us</a>
                    </li>
                    <li class="nav-item <?= isActive('/contact') ?>">
                        <a class="nav-link" href="/DACS/contact">Contact</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="bookingsDropdown" role="button" 
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-calendar-alt mr-1"></i>My Bookings
                            </a>
                            <div class="dropdown-menu" aria-labelledby="bookingsDropdown">
                                <a class="dropdown-item" href="/DACS/bookings">Upcoming Bookings</a>
                                <?php if (isset($_SESSION['is_guide']) && $_SESSION['is_guide']): ?>
                                    <a class="dropdown-item" href="/DACS/guide/bookings">My Guide Requests</a>
                                <?php endif; ?>
                            </div>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle mr-1"></i><?= htmlspecialchars($_SESSION['name'] ?? 'Account') ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="/DACS/profile">My Profile</a>
                                <?php if (isset($_SESSION['is_guide']) && $_SESSION['is_guide']): ?>
                                    <a class="dropdown-item" href="/DACS/guide/profile">Guide Profile</a>
                                    <a class="dropdown-item" href="/DACS/guide/earnings">My Earnings</a>
                                <?php else: ?>
                                    <a class="dropdown-item" href="/DACS/guides/create">Become a Local Guide</a>
                                <?php endif; ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/DACS/logout">Logout</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item <?= isActive('/login') ?>">
                            <a class="nav-link" href="/DACS/login">Login</a>
                        </li>
                        <li class="nav-item <?= isActive('/register') ?>">
                            <a class="nav-link btn btn-outline-light btn-sm mt-1 ml-2" href="/DACS/register">Sign Up</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success'] ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <div class="container mt-4">
    <!-- Content starts here -->
