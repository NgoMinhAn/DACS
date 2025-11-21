<!DOCTYPE html>
<?php if (session_status() === PHP_SESSION_NONE) { @session_start(); } ?>
<html lang="<?php echo function_exists('getLocale') ? getLocale() : 'en'; ?>">

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

<body class="d-flex flex-column min-vh-100">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo url(); ?>">
                <img src="<?php echo url('public/img/logo.png'); ?>" alt="<?php echo SITE_NAME; ?>" class="logo-img me-2">
                <span class="logo-text"><?php echo SITE_NAME; ?></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url(); ?>"><?php echo __('nav.home'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('tourGuide/browse'); ?>"><?php echo __('nav.find_guide'); ?></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <?php echo __('nav.guide_categories'); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo url('tourGuide/category/city'); ?>">City
                                    Tours</a></li>
                            <li><a class="dropdown-item"
                                    href="<?php echo url('tourGuide/category/adventure'); ?>">Adventure Tours</a></li>
                            <li><a class="dropdown-item"
                                    href="<?php echo url('tourGuide/category/cultural'); ?>">Cultural Tours</a></li>
                            <li><a class="dropdown-item" href="<?php echo url('tourGuide/category/food'); ?>">Food
                                    Tours</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                                <li><a class="dropdown-item" href="<?php echo url('tourGuide/category/categories'); ?>"><?php echo __('category.view_all'); ?></a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('about'); ?>"><?php echo __('nav.about'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo url('contact'); ?>"><?php echo __('nav.contact'); ?></a>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-globe"></i> <?php echo strtoupper(function_exists('getLocale') ? getLocale() : 'EN'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                            <li><a class="dropdown-item" href="<?php echo url('locale/set/en'); ?>">English</a></li>
                            <li><a class="dropdown-item" href="<?php echo url('locale/set/vi'); ?>">Tiếng Việt</a></li>
                        </ul>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?php echo $_SESSION['user_name']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if ($_SESSION['user_type'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="<?php echo url('admin/dashboard'); ?>">Admin
                                            Dashboard</a></li>
                                <?php elseif ($_SESSION['user_type'] === 'guide'): ?>
                                    <li><a class="dropdown-item" href="<?php echo url('guide/dashboard'); ?>">Guide Dashboard</a></li>
                                    <li><a class="dropdown-item"
                                            href="<?php echo url('tourGuide/profile/' . $_SESSION['user_id']); ?>">View My Public Profile</a></li>
                                    <li><a class="dropdown-item" href="<?php echo url('guide/account-settings'); ?>"><?php echo __('nav.account_settings'); ?></a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="<?php echo url('user/bookings'); ?>">My Bookings</a></li>
                                    <li><a class="dropdown-item" href="<?php echo url('account/settings'); ?>"><?php echo __('nav.account_settings'); ?></a></li>
                                <?php endif; ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?php echo url('account/logout'); ?>"><?php echo __('nav.logout'); ?></a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo url('account/login'); ?>"><?php echo __('nav.login'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo url('account/register'); ?>"><?php echo __('nav.register'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-light text-primary ms-2 px-3 fw-bold"
                                href="<?php echo url('account/register/guide'); ?>"><?php echo __('nav.become_guide'); ?></a>
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