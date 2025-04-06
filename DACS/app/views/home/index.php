<?php
// Set page title
$pageTitle = 'LocalGuides - Connect with Local Tour Guides';

// Define the content view (this file)
$contentView = __FILE__;

// Extra styles specific to this page
$extraStyles = '<link rel="stylesheet" href="/assets/css/home.css">';

// Hero content starts here directly, no output buffering or layout
?>

<!-- Hero Section -->
<div class="hero-section">
    <div class="container hero-content">
        <div class="row">
            <div class="col-md-8">
                <h1 class="display-4 mb-4">Discover Amazing Places with Local Guides</h1>
                <p class="lead mb-4">Connect with experienced tour guides who know the best spots and stories in your destination.</p>
                <a href="/DACS/guides" class="btn btn-primary btn-lg mr-3">Find a Guide</a>
                <a href="/DACS/guides/create" class="btn btn-outline-light btn-lg">Become a Guide</a>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 mb-4 text-center">
            <div class="p-4">
                <i class="fas fa-search fa-3x mb-3 text-primary"></i>
                <h3>Find Guides</h3>
                <p>Search for qualified tour guides based on location, language, and specialities.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4 text-center">
            <div class="p-4">
                <i class="fas fa-calendar-check fa-3x mb-3 text-primary"></i>
                <h3>Book Tours</h3>
                <p>Schedule your perfect tour with just a few clicks and secure online payments.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4 text-center">
            <div class="p-4">
                <i class="fas fa-star fa-3x mb-3 text-primary"></i>
                <h3>Share Experiences</h3>
                <p>Write reviews and share your amazing tour experiences with others.</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5 mt-5">
    <div class="container">
        <h2 class="text-center mb-5">Featured Tour Guides</h2>
        <div class="row">
            <?php if (!empty($featuredGuides)): ?>
                <?php foreach ($featuredGuides as $guide): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card guide-card h-100">
                            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Tour Guide">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($guide['name'] ?? 'Local Guide') ?></h5>
                                <p class="card-text text-muted"><?= htmlspecialchars($guide['speciality'] ?? 'Tour Guide') ?></p>
                                <div class="rating mb-2">
                                    <?php
                                    $rating = round($guide['avg_rating'] ?? 0);
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '<i class="fas fa-star"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                    <span class="ml-1"><?= number_format($guide['avg_rating'] ?? 0, 1) ?> (<?= $guide['review_count'] ?? 0 ?> reviews)</span>
                                </div>
                                <a href="/DACS/guides/show/<?= $guide['id'] ?? 0 ?>" class="btn btn-sm btn-outline-primary">View Profile</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Sample guides if none in database -->
                <div class="col-md-4">
                    <div class="card guide-card">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Tour Guide">
                        <div class="card-body">
                            <h5 class="card-title">John Doe</h5>
                            <p class="card-text text-muted">Speciality: Historical Tours</p>
                            <div class="rating mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="ml-1">4.5 (120 reviews)</span>
                            </div>
                            <a href="#" class="btn btn-sm btn-outline-primary">View Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card guide-card">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Tour Guide">
                        <div class="card-body">
                            <h5 class="card-title">Jane Smith</h5>
                            <p class="card-text text-muted">Speciality: Food Tours</p>
                            <div class="rating mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="ml-1">5.0 (87 reviews)</span>
                            </div>
                            <a href="#" class="btn btn-sm btn-outline-primary">View Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card guide-card">
                        <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Tour Guide">
                        <div class="card-body">
                            <h5 class="card-title">Michael Johnson</h5>
                            <p class="card-text text-muted">Speciality: Adventure Tours</p>
                            <div class="rating mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <span class="ml-1">4.0 (65 reviews)</span>
                            </div>
                            <a href="#" class="btn btn-sm btn-outline-primary">View Profile</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="/DACS/guides" class="btn btn-primary">Browse All Guides</a>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <h2 class="mb-4">Why Choose Our Platform?</h2>
            <ul class="list-unstyled">
                <li class="mb-3"><i class="fas fa-check-circle text-success mr-2"></i> Verified and experienced local guides</li>
                <li class="mb-3"><i class="fas fa-check-circle text-success mr-2"></i> Customizable tour experiences</li>
                <li class="mb-3"><i class="fas fa-check-circle text-success mr-2"></i> Secure booking and payment system</li>
                <li class="mb-3"><i class="fas fa-check-circle text-success mr-2"></i> Transparent pricing with no hidden fees</li>
                <li class="mb-3"><i class="fas fa-check-circle text-success mr-2"></i> 24/7 customer support</li>
            </ul>
            <a href="/DACS/about" class="btn btn-outline-primary mt-3">Learn More</a>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">Become a Tour Guide</h3>
                    <p class="card-text">Share your knowledge and passion for your city or region. Set your own schedule and prices.</p>
                    <ul>
                        <li>Create your profile and showcase your expertise</li>
                        <li>Set your availability and tour offerings</li>
                        <li>Earn money doing what you love</li>
                    </ul>
                    <a href="/DACS/guides/create" class="btn btn-primary">Sign Up as Guide</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Latest Reviews Section -->
<?php if (!empty($latestReviews)): ?>
<div class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-5">Latest Reviews</h2>
        <div class="row">
            <?php foreach ($latestReviews as $review): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://via.placeholder.com/50" class="rounded-circle mr-3" alt="User">
                                <div>
                                    <h5 class="mb-0"><?= htmlspecialchars($review['user_name'] ?? 'User') ?></h5>
                                    <p class="text-muted mb-0">for <?= htmlspecialchars($review['guide_name'] ?? 'Guide') ?></p>
                                </div>
                            </div>
                            <div class="rating mb-2">
                                <?php
                                $rating = $review['rating'] ?? 0;
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $rating) {
                                        echo '<i class="fas fa-star text-warning"></i>';
                                    } else {
                                        echo '<i class="far fa-star text-warning"></i>';
                                    }
                                }
                                ?>
                            </div>
                            <p class="card-text">"<?= htmlspecialchars($review['comment'] ?? 'Great experience!') ?>"</p>
                            <small class="text-muted"><?= date('M d, Y', strtotime($review['created_at'] ?? 'now')) ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>