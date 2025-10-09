<!-- Hero Section -->
<div class="hero-banner position-relative" style="background: url('<?php echo url('public/img/saigon-cathedral.jpeg'); ?>') center center/cover no-repeat; min-height: 400px;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background:rgba(0,0,0,0.35);"></div>
    <div class="container h-100 position-relative" style="z-index:2; min-height: 400px;">
        <div class="row align-items-center h-100">
            <div class="col-lg-7 mx-auto text-center text-white">
                <h1 class="display-4 fw-bold mb-3" style="text-shadow:0 2px 8px rgba(0,0,0,0.4)"><?php echo $title; ?></h1>
                <p class="lead mb-4" style="text-shadow:0 2px 8px rgba(0,0,0,0.3)"><?php echo $subtitle; ?></p>
                <form action="<?php echo url('tourGuide/search'); ?>" method="GET" class="mt-4 mb-3">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" name="q" placeholder="What kind of guide are you looking for?">
                        <button class="btn btn-success d-flex align-items-center px-2" type="submit" style="font-weight: 600; letter-spacing: 1px;">
                            <i class="fas fa-search me-2"></i> Find a Guide
                        </button>
                    </div>
                </form>
                <p class="small text-white">Popular searches: City tours, Food experiences, Historical guides, Adventure guides</p>
            </div>
        </div>
    </div>
</div>

<!-- Featured Guides Section -->
<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Our Top-Rated Guides</h2>
                <p class="text-muted">Connect with our highest-rated local experts for an unforgettable experience</p>
            </div>
        </div>
        
        <div class="row">
            <!-- Featured Guides from Database -->
            <?php if(!empty($featured_guides)): ?>
                <?php foreach($featured_guides as $index => $guide): ?>
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php if($index < 3): ?>
                                <div class="position-absolute top-0 start-0 p-2">
                                    <span class="badge bg-danger">Top <?php echo $index + 1; ?></span>
                                </div>
                            <?php endif; ?>
                            <img src="<?php echo url('assets/images/profiles/' . ($guide->profile_image ?? 'default.jpg')); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($guide->name); ?>" 
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($guide->name); ?></h5>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-star"></i> <?php echo number_format($guide->avg_rating, 1); ?>
                                    </span>
                                </div>
                                <p class="card-text text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($guide->location); ?>
                                </p>
                                <p class="card-text"><?php echo htmlspecialchars(substr($guide->bio ?? 'Expert local guide', 0, 60)) . '...'; ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary fw-bold">$<?php echo number_format($guide->hourly_rate, 2); ?>/hour</span>
                                    <a href="<?php echo url('tourGuide/profile/' . $guide->guide_id); ?>" class="btn btn-sm btn-outline-primary">View Profile</a>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                <small class="text-muted"><?php echo $guide->specialties ?? 'Various specialties'; ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> No featured guides available at the moment. Check back soon!
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-3">
            <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-primary">Browse All Guides</a>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">How It Works</h2>
                <p class="text-muted">Easy steps to connect with your perfect guide</p>
            </div>
        </div>
        
        <div class="row" id="how-it-works-steps">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100 border-0 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-search fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">1. Find Your Guide</h5>
                        <p class="card-text text-muted">Browse profiles of experienced local guides and find one that matches your interests and needs.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card h-100 border-0 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-comments fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">2. Connect Directly</h5>
                        <p class="card-text text-muted">Message your chosen guide to discuss your interests, dates, and personalize your experience.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-map-marked-alt fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">3. Enjoy Your Experience</h5>
                        <p class="card-text text-muted">Meet your guide and enjoy a personalized experience crafted just for you.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-none" id="how-it-works-more">
            <div class="col-md-4 mb-4 mb-md-0 mt-4">
                <div class="card h-100 border-0 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-lock fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">4. Secure Your Booking</h5>
                        <p class="card-text text-muted">Confirm your guide and make a secure payment through our trusted platform.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0 mt-4">
                <div class="card h-100 border-0 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-lightbulb fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">5. Get Travel Tips</h5>
                        <p class="card-text text-muted">Receive custom tips, local insights, and preparation advice tailored to your destination.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4">
                <div class="card h-100 border-0 text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-share-alt fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">6. Share Your Journey</h5>
                        <p class="card-text text-muted">Leave a review and share your experience to help future travelers make informed choices.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="#" class="btn btn-outline-primary" id="how-it-works-learn-more">Learn More</a>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.getElementById('how-it-works-learn-more');
            var more = document.getElementById('how-it-works-more');
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                more.classList.remove('d-none');
                btn.style.display = 'none';
            });
        });
        </script>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-light mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">What Our Travelers Say</h2>
                <p class="text-muted">Real experiences from people who connected with their perfect guides</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text">"Our guide Maria was incredible! She knew the city inside out and took us to places we would have never found on our own. The direct connection made the experience so much more personal."</p>
                        <div class="d-flex align-items-center">
                            <img src="<?php echo url('public/img/testimonial1.jpg'); ?>" class="rounded-circle me-3" width="50" height="50" alt="Testimonial">
                            <div>
                                <h6 class="mb-0">John Smith</h6>
                                <small class="text-muted">New York, USA</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text">"The best part of our trip was having Carlos as our guide. He was knowledgeable, fun, and adapted the tour to our interests. Being able to work directly with him made all the difference."</p>
                        <div class="d-flex align-items-center">
                            <img src="<?php echo url('public/img/testimonial2.jpg'); ?>" class="rounded-circle me-3" width="50" height="50" alt="Testimonial">
                            <div>
                                <h6 class="mb-0">Emily Johnson</h6>
                                <small class="text-muted">London, UK</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="card-text">"We hired Akiko as our guide in Kyoto and it was the highlight of our Japan trip. Her local knowledge and personal connections gave us insights no tour company could provide."</p>
                        <div class="d-flex align-items-center">
                            <img src="<?php echo url('public/img/testimonial3.jpg'); ?>" class="rounded-circle me-3" width="50" height="50" alt="Testimonial">
                            <div>
                                <h6 class="mb-0">Michael Chen</h6>
                                <small class="text-muted">Toronto, Canada</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section - Become a Guide -->
<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bg-primary text-white p-5 rounded-3">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-4 mb-lg-0">
                            <h2 class="fw-bold">Are You a Tour Guide?</h2>
                            <p class="lead mb-0">Join our platform and connect directly with travelers seeking your expertise. No middleman, higher earnings, and full control of your services.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="<?php echo url('account/register/guide'); ?>" class="btn btn-light btn-lg">Become a Guide</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dynamic Top Rated Guides Section -->
<?php
require_once __DIR__ . '/../../config/config.php';
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    $stmt = $pdo->query('SELECT * FROM guide_listings ORDER BY avg_rating DESC LIMIT 5');
    $topGuides = $stmt->fetchAll();
} catch (Exception $e) {
    error_log('Error fetching top rated guides: ' . $e->getMessage());
    $topGuides = [];
}
?>
<section id="top-rated-guides" class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Top Rated Guides</h2>
                <p class="text-muted">Connect with our highest-rated local experts for an unforgettable experience</p>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($topGuides as $guide): ?>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?php echo url('assets/images/profiles/' . ($guide['profile_image'] ?? 'default.jpg')); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($guide['name']); ?>" 
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0"><?php echo htmlspecialchars($guide['name']); ?></h5>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star"></i> <?php echo number_format($guide['avg_rating'], 1); ?>
                                </span>
                            </div>
                            <p class="card-text text-muted small mb-2">
                                <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($guide['location']); ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">$<?php echo number_format($guide['hourly_rate'], 2); ?>/hour</span>
                                <a href="<?php echo url('tourGuide/profile/' . $guide['guide_id']); ?>" class="btn btn-sm btn-outline-primary">View Profile</a>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <small class="text-muted"><?php echo $guide['specialties'] ?? 'Various specialties'; ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
    .input-group .form-control::placeholder {
        color: #888 !important;
        opacity: 1;
    }
    .btn-success:hover, .btn-success:focus {
        background: #218838 !important;
    }
</style>