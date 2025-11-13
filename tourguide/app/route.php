<?php

$routes = [
    // Home and static pages
    ['pattern' => '#^$#', 'controller' => 'TourGuideController', 'method' => 'index', 'params' => []],
    ['pattern' => '#^about$#', 'controller' => 'PageController', 'method' => 'about', 'params' => []],
    ['pattern' => '#^contact$#', 'controller' => 'PageController', 'method' => 'contact', 'params' => []],

    // TourGuide routes
    ['pattern' => '#^tourGuide/browse$#', 'controller' => 'TourGuideController', 'method' => 'browse', 'params' => []],
    ['pattern' => '#^tourGuide/profile/(\d+)$#', 'controller' => 'TourGuideController', 'method' => 'profile', 'params' => [1]],
    ['pattern' => '#^tourGuide/category/([a-zA-Z0-9\-]+)$#', 'controller' => 'TourGuideController', 'method' => 'category', 'params' => [1]],
    ['pattern' => '#^tourGuide/contact/(\d+)$#', 'controller' => 'TourGuideController', 'method' => 'contact', 'params' => [1]],
    ['pattern' => '#^tourGuide/confirmBooking$#', 'controller' => 'TourGuideController', 'method' => 'confirmBooking', 'params' => []],
    ['pattern' => '#^tourGuide/saveBooking$#', 'controller' => 'TourGuideController', 'method' => 'saveBooking', 'params' => []],
    ['pattern' => '#^tourGuide/search$#', 'controller' => 'TourGuideController', 'method' => 'search', 'params' => []],
    ['pattern' => '#^tourGuide/review/(\d+)$#', 'controller' => 'TourGuideController', 'method' => 'review', 'params' => [1]],
    ['pattern' => '#^tourGuide/submitReview$#', 'controller' => 'TourGuideController', 'method' => 'submitReview', 'params' => []],
    ['pattern' => '#^tourGuide/settings$#', 'controller' => 'TourGuideController', 'method' => 'settings', 'params' => []],
    ['pattern' => '#^tourGuide/delete-account$#', 'controller' => 'TourGuideController', 'method' => 'deleteAccount', 'params' => []],
    ['pattern' => '#^tourGuide/chat/(\d+)$#', 'controller' => 'TourGuideController', 'method' => 'chat', 'params' => [1]],

    // Guide routes
    ['pattern' => '#^guide/dashboard$#', 'controller' => 'GuideController', 'method' => 'dashboard', 'params' => []],
    ['pattern' => '#^guide/reviews$#', 'controller' => 'GuideController', 'method' => 'reviewsList', 'params' => []],
    ['pattern' => '#^guide/contacts$#', 'controller' => 'GuideController', 'method' => 'contactRequests', 'params' => []],
    ['pattern' => '#^guide/bookings$#', 'controller' => 'GuideController', 'method' => 'bookingsList', 'params' => []],
    ['pattern' => '#^guide/booking/(\d+)$#', 'controller' => 'GuideController', 'method' => 'booking', 'params' => [1]],
    ['pattern' => '#^guide/acceptBooking/(\d+)$#', 'controller' => 'GuideController', 'method' => 'acceptBooking', 'params' => [1]],
    ['pattern' => '#^guide/declineBooking/(\d+)$#', 'controller' => 'GuideController', 'method' => 'declineBooking', 'params' => [1]],
    ['pattern' => '#^guide/chat/(\d+)$#', 'controller' => 'GuideController', 'method' => 'chat', 'params' => [1]],
    ['pattern' => '#^guide/calendar$#', 'controller' => 'GuideController', 'method' => 'calendar', 'params' => []],
    ['pattern' => '#^guide/edit-profile$#', 'controller' => 'GuideController', 'method' => 'editProfile', 'params' => []],
    ['pattern' => '#^guide/update-profile$#', 'controller' => 'GuideController', 'method' => 'updateProfile', 'params' => []],
    ['pattern' => '#^guide/update-rates$#', 'controller' => 'GuideController', 'method' => 'updateRates', 'params' => []],
    ['pattern' => '#^guide/update-availability$#', 'controller' => 'GuideController', 'method' => 'updateAvailability', 'params' => []],
    ['pattern' => '#^guide/update-specialties$#', 'controller' => 'GuideController', 'method' => 'updateSpecialties', 'params' => []],
    ['pattern' => '#^guide/update-languages$#', 'controller' => 'GuideController', 'method' => 'updateLanguages', 'params' => []],
    ['pattern' => '#^guide/update-bio$#', 'controller' => 'GuideController', 'method' => 'updateBio', 'params' => []],
    ['pattern' => '#^guide/update-profile-picture$#', 'controller' => 'GuideController', 'method' => 'updateProfilePicture', 'params' => []],
    ['pattern' => '#^guide/account-settings$#', 'controller' => 'GuideController', 'method' => 'accountSettings', 'params' => []],
    ['pattern' => '#^guide/profile-settings$#', 'controller' => 'GuideController', 'method' => 'profileSettings', 'params' => []],
    ['pattern' => '#^guide/password-settings$#', 'controller' => 'GuideController', 'method' => 'passwordSettings', 'params' => []],

    // User routes
    ['pattern' => '#^user/dashboard$#', 'controller' => 'UserController', 'method' => 'dashboard', 'params' => []],
    ['pattern' => '#^user/bookings$#', 'controller' => 'UserController', 'method' => 'bookings', 'params' => []],
    ['pattern' => '#^user/chat/(\d+)$#', 'controller' => 'UserController', 'method' => 'chat', 'params' => [1]],
    ['pattern' => '#^user/booking/(\d+)$#', 'controller' => 'UserController', 'method' => 'bookingDetail', 'params' => [1]],
    ['pattern' => '#^user/invoice/(\d+)$#', 'controller' => 'UserController', 'method' => 'invoice', 'params' => [1]],
    ['pattern' => '#^user/invoice/print/(\d+)$#', 'controller' => 'UserController', 'method' => 'printInvoice', 'params' => [1]],

    // Message routes
    ['pattern' => '#^messages/mark-delivered$#', 'controller' => 'MessageController', 'method' => 'markDelivered', 'params' => []],
    ['pattern' => '#^messages/mark-read$#', 'controller' => 'MessageController', 'method' => 'markRead', 'params' => []],

    // Admin routes
    ['pattern' => '#^admin/dashboard$#', 'controller' => 'AdminController', 'method' => 'dashboard', 'params' => []],
    ['pattern' => '#^admin/users$#', 'controller' => 'AdminController', 'method' => 'users', 'params' => []],
    ['pattern' => '#^admin/editUser/(\d+)$#', 'controller' => 'AdminController', 'method' => 'editUser', 'params' => [1]],
    ['pattern' => '#^admin/deleteUser/(\d+)$#', 'controller' => 'AdminController', 'method' => 'deleteUser', 'params' => [1]],
    ['pattern' => '#^admin/editGuide/(\d+)$#', 'controller' => 'AdminController', 'method' => 'editGuide', 'params' => [1]],
    ['pattern' => '#^admin/guideApplications$#', 'controller' => 'AdminController', 'method' => 'guideApplications', 'params' => []],
    ['pattern' => '#^admin/guideApplication/(\d+)$#', 'controller' => 'AdminController', 'method' => 'guideApplicationDetail', 'params' => [1]],
    ['pattern' => '#^admin/guides$#', 'controller' => 'AdminController', 'method' => 'guides', 'params' => []],
    ['pattern' => '#^admin/deleteGuide/(\d+)$#', 'controller' => 'AdminController', 'method' => 'deleteGuide', 'params' => [1]],
    ['pattern' => '#^admin/categories$#', 'controller' => 'AdminController', 'method' => 'categories', 'params' => []],
    ['pattern' => '#^admin/addCategory$#', 'controller' => 'AdminController', 'method' => 'addCategory', 'params' => []],
    ['pattern' => '#^admin/editCategory/(\d+)$#', 'controller' => 'AdminController', 'method' => 'editCategory', 'params' => [1]],
    ['pattern' => '#^admin/deleteCategory/(\d+)$#', 'controller' => 'AdminController', 'method' => 'deleteCategory', 'params' => [1]],

    // Account routes
    ['pattern' => '#^account/login$#', 'controller' => 'AccountController', 'method' => 'login', 'params' => []],
    ['pattern' => '#^account/logout$#', 'controller' => 'AccountController', 'method' => 'logout', 'params' => []],
    ['pattern' => '#^account/register$#', 'controller' => 'AccountController', 'method' => 'register', 'params' => []],
    ['pattern' => '#^account/google-login$#', 'controller' => 'AccountController', 'method' => 'googleLogin', 'params' => []],
    ['pattern' => '#^account/google-callback$#', 'controller' => 'AccountController', 'method' => 'googleCallback', 'params' => []],
    ['pattern' => '#^account/forgot-password$#', 'controller' => 'AccountController', 'method' => 'forgotPassword', 'params' => []],
    ['pattern' => '#^account/reset-password/([a-zA-Z0-9]+)$#', 'controller' => 'AccountController', 'method' => 'resetPassword', 'params' => [1]],
    ['pattern' => '#^account/becomeguide$#', 'controller' => 'AccountController', 'method' => 'becomeguide', 'params' => []],
];

function handle_custom_routes($uri, $routes) {
    foreach ($routes as $route) {
        if (preg_match($route['pattern'], $uri, $matches)) {
            require_once CONTROLLER_PATH . '/' . $route['controller'] . '.php';
            $controller = new $route['controller']();
            $params = [];
            foreach ($route['params'] as $index) {
                $params[] = $matches[$index];
            }
            call_user_func_array([$controller, $route['method']], $params);
            exit;
        }
    }
}