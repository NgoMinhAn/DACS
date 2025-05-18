<?php
// Example route definitions
$router->get('guide/booking/{id}', 'GuideController@bookingDetails');
$router->get('guide/bookings', 'GuideController@bookingsList');
$router->get('guide/dashboard', 'GuideController@dashboard');
$routes[] = [
    'pattern' => '#^user/dashboard$#',
    'controller' => 'UserController',
    'method' => 'dashboard',
    'params' => []
];
$routes[] = [
    'pattern' => '#^admin/users$#',
    'controller' => 'AdminController',
    'method' => 'users',
    'params' => []
];
$routes[] = [
    'pattern' => '#^admin/editUser/(\d+)$#',
    'controller' => 'AdminController',
    'method' => 'editUser',
    'params' => [1]
];
$routes[] = [
    'pattern' => '#^admin/deleteUser/(\d+)$#',
    'controller' => 'AdminController',
    'method' => 'deleteUser',
    'params' => [1]
];