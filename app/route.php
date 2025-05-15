<?php

$routes = [
    // Booking details
    ['pattern' => '#^guide/booking/(\d+)$#', 'controller' => 'GuideController', 'method' => 'booking', 'params' => [1]],
    // Accept booking
    ['pattern' => '#^guide/acceptBooking/(\d+)$#', 'controller' => 'GuideController', 'method' => 'acceptBooking', 'params' => [1]],
    // Decline booking
    ['pattern' => '#^guide/declineBooking/(\d+)$#', 'controller' => 'GuideController', 'method' => 'declineBooking', 'params' => [1]],
    // Chat
    ['pattern' => '#^guide/chat/(\d+)$#', 'controller' => 'GuideController', 'method' => 'chat', 'params' => [1]],
    // Add more custom routes as needed
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