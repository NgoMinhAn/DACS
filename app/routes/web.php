<?php
// Example route definitions
$router->get('guide/booking/{id}', 'GuideController@bookingDetails');
$router->get('guide/bookings', 'GuideController@bookingsList');
$router->get('guide/dashboard', 'GuideController@dashboard');
// Add more routes as needed