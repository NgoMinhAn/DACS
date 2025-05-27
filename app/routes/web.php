<?php
// Example route definitions
$router->get('tourGuide/review/:id', 'TourGuideController@review');
$router->post('tourGuide/submitReview', 'TourGuideController@submitReview'); 
$router->get('guide/booking/{id}', 'GuideController@bookingDetails');
$router->get('guide/bookings', 'GuideController@bookingsList');
$router->get('guide/dashboard', 'GuideController@dashboard');
$router->get('guide/reviews', 'GuideController@reviewsList');
$router->get('vnpay/createPayment', 'VNPayController@createPayment');
$router->get('vnpay/return', 'VNPayController@return');
$router->get('guide/contacts', 'GuideController@contactRequests');
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
$routes[] = [
    'pattern' => '#^admin/editGuide/(\d+)$#',
    'controller' => 'AdminController',
    'method' => 'editGuide',
    'params' => [1]
];
$routes['guide/toggle-availability'] = ['GuideController', 'toggleAvailability'];