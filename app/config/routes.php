// Routes for reviews
$router->get('tourGuide/review/:id', 'TourGuideController@review');
$router->post('tourGuide/submitReview', 'TourGuideController@submitReview');

// Routes for guide account management
$router->get('tourGuide/settings', 'TourGuideController@settings');
$router->post('tourGuide/delete-account', 'TourGuideController@deleteAccount'); 