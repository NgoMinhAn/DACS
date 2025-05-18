// Routes for reviews
$router->get('tourGuide/review/:id', 'TourGuideController@review');
$router->post('tourGuide/submitReview', 'TourGuideController@submitReview'); 