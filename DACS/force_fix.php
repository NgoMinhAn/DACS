<?php
// Force fix layout script
echo "<h1>Completely Rebuilding Layout Files</h1>";

// Correct header content without closing HTML tags
$correct_header = '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Local Guides | Connect with Experts</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background-image: url(\'/DACS/assests/images/hero-bg.jpg\');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            position: relative;
        }
        
        .hero-section::after {
            content: \'\';
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
    // Start the session if it\'s not started already
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Function to check if current page matches the given path
    if (!function_exists(\'isActive\')) {
        function isActive($path) {
            $current_page = $_SERVER[\'REQUEST_URI\'];
            
            // Handle base path
            if ($path === \'/\') {
                return ($current_page === \'/DACS/\' || $current_page === \'/DACS\') ? \'active\' : \'\';
            }
            
            // Add /DACS prefix to path for comparison
            $prefixed_path = \'/DACS\' . $path;
            return strpos($current_page, $prefixed_path) === 0 ? \'active\' : \'\';
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
                    <li class="nav-item <?= isActive(\'/\') ?>">
                        <a class="nav-link" href="/DACS/">Home</a>
                    </li>
                    <li class="nav-item <?= isActive(\'/guides\') ?>">
                        <a class="nav-link" href="/DACS/guides">Find a Guide</a>
                    </li>
                    <li class="nav-item <?= isActive(\'/about\') ?>">
                        <a class="nav-link" href="/DACS/about">About Us</a>
                    </li>
                    <li class="nav-item <?= isActive(\'/contact\') ?>">
                        <a class="nav-link" href="/DACS/contact">Contact</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <?php if (isset($_SESSION[\'user_id\'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="bookingsDropdown" role="button" 
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-calendar-alt mr-1"></i>My Bookings
                            </a>
                            <div class="dropdown-menu" aria-labelledby="bookingsDropdown">
                                <a class="dropdown-item" href="/DACS/bookings">Upcoming Bookings</a>
                                <?php if (isset($_SESSION[\'is_guide\']) && $_SESSION[\'is_guide\']): ?>
                                    <a class="dropdown-item" href="/DACS/guide/bookings">My Guide Requests</a>
                                <?php endif; ?>
                            </div>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle mr-1"></i><?= htmlspecialchars($_SESSION[\'name\'] ?? \'Account\') ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="/DACS/profile">My Profile</a>
                                <?php if (isset($_SESSION[\'is_guide\']) && $_SESSION[\'is_guide\']): ?>
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
                        <li class="nav-item <?= isActive(\'/login\') ?>">
                            <a class="nav-link" href="/DACS/login">Login</a>
                        </li>
                        <li class="nav-item <?= isActive(\'/register\') ?>">
                            <a class="nav-link btn btn-outline-light btn-sm mt-1 ml-2" href="/DACS/register">Sign Up</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php if (isset($_SESSION[\'success\'])): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION[\'success\'] ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <?php unset($_SESSION[\'success\']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION[\'errors\']) && is_array($_SESSION[\'errors\']) && !empty($_SESSION[\'errors\'])): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php foreach ($_SESSION[\'errors\'] as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <?php unset($_SESSION[\'errors\']); ?>
    <?php endif; ?>

    <div class="container mt-4">
    <!-- Content starts here -->
';

// Correct footer content 
$correct_footer = '    </div><!-- Close the container div -->

    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-map-marked-alt mr-2"></i>LocalGuides</h5>
                    <p class="text-muted">Connect with experienced local guides for personalized tours anywhere in the world.</p>
                    <div class="social-links">
                        <a href="#" class="text-white mr-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white mr-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white mr-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>For Tourists</h5>
                    <ul class="list-unstyled">
                        <li><a href="/DACS/guides" class="text-light">Find Guides</a></li>
                        <li><a href="/DACS/bookings" class="text-light">My Bookings</a></li>
                        <li><a href="/DACS/reviews" class="text-light">Write Reviews</a></li>
                        <li><a href="/DACS/faq" class="text-light">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>For Guides</h5>
                    <ul class="list-unstyled">
                        <li><a href="/DACS/guides/create" class="text-light">Join as Guide</a></li>
                        <li><a href="/DACS/guide/bookings" class="text-light">Manage Bookings</a></li>
                        <li><a href="/DACS/guide/earnings" class="text-light">Earnings</a></li>
                        <li><a href="/DACS/guide/profile" class="text-light">Guide Profile</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt mr-2"></i> 123 Tour Street, City, Country</li>
                        <li><i class="fas fa-phone mr-2"></i> +1 (555) 123-4567</li>
                        <li><i class="fas fa-envelope mr-2"></i> info@localguides.com</li>
                    </ul>
                    <form class="mt-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Enter your email">
                            <div class="input-group-append">
                                <button class="btn btn-outline-light" type="button">Subscribe</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="bg-light">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?= date(\'Y\') ?> LocalGuides. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="/DACS/terms" class="text-light">Terms of Service</a></li>
                        <li class="list-inline-item"><a href="/DACS/privacy" class="text-light">Privacy Policy</a></li>
                        <li class="list-inline-item"><a href="/DACS/cookies" class="text-light">Cookies</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Enable Bootstrap tooltips
        $(function () {
            $([\'data-toggle="tooltip"\''.']).tooltip();
        });
        
        // Auto-hide alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $(".alert").alert(\'close\');
            }, 5000);
        });
    </script>
</body>
</html>';

// Define paths
define('APP_PATH', __DIR__ . '/app');
define('VIEWS_PATH', APP_PATH . '/views');
define('SHARES_PATH', VIEWS_PATH . '/shares');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');

// Check and fix paths
if (!is_dir(VIEWS_PATH)) {
    echo "<p style='color:red'>Views directory not found at: " . VIEWS_PATH . "</p>";
    exit;
}

if (!is_dir(SHARES_PATH)) {
    echo "<p>Creating shares directory...</p>";
    mkdir(SHARES_PATH, 0777, true);
}

// Completely replace header.php
$header_file = SHARES_PATH . '/header.php';
if (file_put_contents($header_file, $correct_header)) {
    echo "<p style='color:green'>✓ Header file completely replaced!</p>";
} else {
    echo "<p style='color:red'>✗ Failed to replace header file!</p>";
}

// Completely replace footer.php
$footer_file = SHARES_PATH . '/footer.php';
if (file_put_contents($footer_file, $correct_footer)) {
    echo "<p style='color:green'>✓ Footer file completely replaced!</p>";
} else {
    echo "<p style='color:red'>✗ Failed to replace footer file!</p>";
}

// Check for incorrectly nested file includes in HomeController
$home_controller = CONTROLLERS_PATH . '/HomeController.php';
if (file_exists($home_controller)) {
    $content = file_get_contents($home_controller);
    // Check for nested includes
    if (preg_match('/require_once.*?header.*?include.*?index.*?require_once.*?footer/s', $content)) {
        echo "<p>Found potentially correct include pattern in HomeController</p>";
    } else {
        echo "<p style='color:orange'>Warning: HomeController may have incorrect include pattern</p>";
    }
} else {
    echo "<p style='color:orange'>HomeController not found at expected location</p>";
}

// Create a test.php file in the root directory
$test_file = __DIR__ . '/test.php';
$test_content = '<?php
require_once "app/views/shares/header.php";
echo "<h1>Test Page</h1>";
echo "<p>This is a test page to verify that the layout system works correctly.</p>";
require_once "app/views/shares/footer.php";
?>';

if (file_put_contents($test_file, $test_content)) {
    echo "<p style='color:green'>✓ Created test.php file to verify layout</p>";
} else {
    echo "<p style='color:red'>✗ Failed to create test file!</p>";
}

echo "<h2>Layout files have been completely rebuilt!</h2>";
echo "<p>Next steps:</p>";
echo "<ol>";
echo "<li>Visit <a href='/DACS/test.php'>test.php</a> to check if the layout works correctly</li>";
echo "<li>Then try your main site at <a href='/DACS/'>home page</a></li>";
echo "</ol>";
?> 