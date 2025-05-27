<?php

$routes = [
    // Account routes
    ['pattern' => '#^account/google-login$#', 'controller' => 'AccountController', 'method' => 'googleLogin', 'params' => []],
    ['pattern' => '#^account/google-callback$#', 'controller' => 'AccountController', 'method' => 'googleCallback', 'params' => []],
    ['pattern' => '#^account/forgot-password$#', 'controller' => 'AccountController', 'method' => 'forgotPassword', 'params' => []],
    ['pattern' => '#^account/reset-password/([a-zA-Z0-9]+)$#', 'controller' => 'AccountController', 'method' => 'resetPassword', 'params' => [1]],
    
    // Guide routes
    ['pattern' => '#^guide/dashboard$#', 'controller' => 'GuideController', 'method' => 'dashboard', 'params' => []],
    ['pattern' => '#^guide/reviews$#', 'controller' => 'GuideController', 'method' => 'reviewsList', 'params' => []],
    ['pattern' => '#^guide/contacts$#', 'controller' => 'GuideController', 'method' => 'contactRequests', 'params' => []],
    ['pattern' => '#^guide/bookings$#', 'controller' => 'GuideController', 'method' => 'bookingsList', 'params' => []],
    ['pattern' => '#^guide/booking/(\d+)$#', 'controller' => 'GuideController', 'method' => 'booking', 'params' => [1]],
    ['pattern' => '#^guide/acceptBooking/(\d+)$#', 'controller' => 'GuideController', 'method' => 'acceptBooking', 'params' => [1]],
    ['pattern' => '#^guide/declineBooking/(\d+)$#', 'controller' => 'GuideController', 'method' => 'declineBooking', 'params' => [1]],
    ['pattern' => '#^guide/chat/(\d+)$#', 'controller' => 'GuideController', 'method' => 'chat', 'params' => [1]],
    ['pattern' => '#^guide/toggle-availability$#', 'controller' => 'GuideController', 'method' => 'toggleAvailability', 'params' => []],
    ['pattern' => '#^guide/edit-profile$#', 'controller' => 'GuideController', 'method' => 'editProfile', 'params' => []],
    ['pattern' => '#^guide/update-rates$#', 'controller' => 'GuideController', 'method' => 'updateRates', 'params' => []],
    ['pattern' => '#^guide/update-profile$#', 'controller' => 'GuideController', 'method' => 'updateProfile', 'params' => []],
    ['pattern' => '#^guide/update-availability$#', 'controller' => 'GuideController', 'method' => 'updateAvailability', 'params' => []],
    ['pattern' => '#^guide/update-specialties$#', 'controller' => 'GuideController', 'method' => 'updateSpecialties', 'params' => []],
    ['pattern' => '#^guide/update-languages$#', 'controller' => 'GuideController', 'method' => 'updateLanguages', 'params' => []],
    ['pattern' => '#^guide/update-bio$#', 'controller' => 'GuideController', 'method' => 'updateBio', 'params' => []],
    ['pattern' => '#^guide/update-profile-picture$#', 'controller' => 'GuideController', 'method' => 'updateProfilePicture', 'params' => []],
    ['pattern' => '#^guide/calendar$#', 'controller' => 'GuideController', 'method' => 'calendar', 'params' => []],
    ['pattern' => '#^guide/account-settings$#', 'controller' => 'GuideController', 'method' => 'accountSettings', 'params' => []],
    ['pattern' => '#^guide/profile-settings$#', 'controller' => 'GuideController', 'method' => 'profileSettings', 'params' => []],
    ['pattern' => '#^guide/password-settings$#', 'controller' => 'GuideController', 'method' => 'passwordSettings', 'params' => []],
    // User routes
    ['pattern' => '#^user/chat/(\d+)$#', 'controller' => 'UserController', 'method' => 'chat', 'params' => [1]],
    ['pattern' => '#^user/booking/(\d+)$#', 'controller' => 'UserController', 'method' => 'bookingDetail', 'params' => [1]],
    // Admin routes
    ['pattern' => '#^admin/users$#', 'controller' => 'AdminController', 'method' => 'users', 'params' => []],
    ['pattern' => '#^admin/editUser/(\d+)$#', 'controller' => 'AdminController', 'method' => 'editUser', 'params' => [1]],
    ['pattern' => '#^admin/deleteUser/(\d+)$#', 'controller' => 'AdminController', 'method' => 'deleteUser', 'params' => [1]],
    ['pattern' => '#^admin/editGuide/(\d+)$#', 'controller' => 'AdminController', 'method' => 'editGuide', 'params' => [1]],
    ['pattern' => '#^admin/guideApplications$#', 'controller' => 'AdminController', 'method' => 'guideApplications', 'params' => []],
    ['pattern' => '#^admin/guideApplication/(\d+)$#', 'controller' => 'AdminController', 'method' => 'guideApplicationDetail', 'params' => [1]]
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