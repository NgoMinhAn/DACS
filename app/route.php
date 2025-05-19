<?php

$routes = [
    // Guide routes
    ['pattern' => '#^guide/dashboard$#', 'controller' => 'GuideController', 'method' => 'dashboard', 'params' => []],
    ['pattern' => '#^guide/reviews$#', 'controller' => 'GuideController', 'method' => 'reviewsList', 'params' => []],
    ['pattern' => '#^guide/bookings$#', 'controller' => 'GuideController', 'method' => 'bookingsList', 'params' => []],
    ['pattern' => '#^guide/booking/(\d+)$#', 'controller' => 'GuideController', 'method' => 'booking', 'params' => [1]],
    ['pattern' => '#^guide/acceptBooking/(\d+)$#', 'controller' => 'GuideController', 'method' => 'acceptBooking', 'params' => [1]],
    ['pattern' => '#^guide/declineBooking/(\d+)$#', 'controller' => 'GuideController', 'method' => 'declineBooking', 'params' => [1]],
    ['pattern' => '#^guide/chat/(\d+)$#', 'controller' => 'GuideController', 'method' => 'chat', 'params' => [1]],
    // User routes
    ['pattern' => '#^user/chat/(\d+)$#', 'controller' => 'UserController', 'method' => 'chat', 'params' => [1]],
    // Admin routes
    ['pattern' => '#^admin/users$#', 'controller' => 'AdminController', 'method' => 'users', 'params' => []],
    ['pattern' => '#^admin/editUser/(\d+)$#', 'controller' => 'AdminController', 'method' => 'editUser', 'params' => [1]],
    ['pattern' => '#^admin/deleteUser/(\d+)$#', 'controller' => 'AdminController', 'method' => 'deleteUser', 'params' => [1]],
    ['pattern' => '#^admin/editGuide/(\d+)$#', 'controller' => 'AdminController', 'method' => 'editGuide', 'params' => [1]]
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