<!-- Hero Section -->
<div class="bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold"><?php echo $title; ?></h1>
                <p class="lead"><?php echo $subtitle; ?></p>
                <form action="<?php echo url('tourGuide/search'); ?>" method="GET" class="mt-4 mb-3">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" name="q" placeholder="What kind of guide are you looking for?">
                        <button class="btn btn-success" type="submit">Find a Guide</button>
                    </div>
                </form>
                <p class="small text-white-50">Popular searches: City tours, Food experiences, Historical guides, Adventure guides</p>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="<?php echo url('public/img/hero-image.jpg'); ?>" alt="Find a tour guide" class="img-fluid rounded-3 shadow-lg">
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

<!-- Guide Categories Section -->
<section class="mb-5 py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Find Guides by Category</h2>
                <p class="text-muted">Discover guides specialized in your areas of interest</p>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Guide Categories from Database -->
            <?php if(!empty($guide_categories)): ?>
                <?php foreach($guide_categories as $category): ?>
                    <div class="col-md-6 col-lg-3">
                        <a href="<?php echo url('tourGuide/category/' . urlencode($category->name)); ?>" class="text-decoration-none">
                            <div class="card h-100 shadow-sm category-card">
                                <img src="<?php echo url('public/img/category-' . strtolower(str_replace(' ', '-', $category->name)) . '.jpg'); ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($category->name); ?> Tours"
                                     onerror="this.src='<?php echo url('public/img/category-default.jpg'); ?>'">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo htmlspecialchars($category->name); ?></h5>
                                    <p class="card-text text-muted small"><?php echo htmlspecialchars($category->description ?? 'Explore with our experienced guides'); ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback Categories if None in Database -->
                <div class="col-md-6 col-lg-3">
                    <a href="<?php echo url('tourGuide/category/city'); ?>" class="text-decoration-none">
                        <div class="card h-100 shadow-sm category-card">
                            <img src="<?php echo url('public/img/category-city.jpg'); ?>" class="card-img-top" alt="City Tours">
                            <div class="card-body text-center">
                                <h5 class="card-title">City Guides</h5>
                                <p class="card-text text-muted small">Explore urban landscapes with expert city guides</p>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <a href="<?php echo url('tourGuide/category/adventure'); ?>" class="text-decoration-none">
                        <div class="card h-100 shadow-sm category-card">
                            <img src="<?php echo url('public/img/category-adventure.jpg'); ?>" class="card-img-top" alt="Adventure Tours">
                            <div class="card-body text-center">
                                <h5 class="card-title">Adventure Guides</h5>
                                <p class="card-text text-muted small">Thrilling experiences with adventure specialists</p>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <a href="<?php echo url('tourGuide/category/cultural'); ?>" class="text-decoration-none">
                        <div class="card h-100 shadow-sm category-card">
                            <img src="<?php echo url('public/img/category-cultural.jpg'); ?>" class="card-img-top" alt="Cultural Tours">
                            <div class="card-body text-center">
                                <h5 class="card-title">Cultural Guides</h5>
                                <p class="card-text text-muted small">Immerse yourself in local culture and history</p>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <a href="<?php echo url('tourGuide/category/food'); ?>" class="text-decoration-none">
                        <div class="card h-100 shadow-sm category-card">
                            <img src="<?php echo url('public/img/category-food.jpg'); ?>" class="card-img-top" alt="Food Tours">
                            <div class="card-body text-center">
                                <h5 class="card-title">Food & Cuisine Guides</h5>
                                <p class="card-text text-muted small">Taste local flavors with culinary experts</p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">How It Works</h2>
                <p class="text-muted">Easy steps to connect with your perfect guide</p>
            </div>
        </div>
        
        <div class="row">
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
        
        <div class="text-center mt-4">
            <a href="<?php echo url('guides/how-it-works'); ?>" class="btn btn-outline-primary">Learn More</a>
        </div>
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