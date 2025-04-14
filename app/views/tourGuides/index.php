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
                <h2 class="fw-bold">Meet Our Top-Rated Guides</h2>
                <p class="text-muted">Connect directly with experienced local guides who will make your trip unforgettable</p>
            </div>
        </div>
        
        <div class="row">
            <!-- Sample Featured Guides (to be replaced with actual data) -->
            <?php for($i = 1; $i <= 4; $i++): ?>
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?php echo url('public/img/guide' . $i . '.jpg'); ?>" class="card-img-top" alt="Tour Guide">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">Guide Name <?php echo $i; ?></h5>
                                <span class="badge bg-warning text-dark"><i class="fas fa-star"></i> 4.<?php echo rand(7, 9); ?></span>
                            </div>
                            <p class="card-text text-muted small mb-2">
                                <i class="fas fa-map-marker-alt"></i> City Name, Country
                            </p>
                            <p class="card-text">Expert in history, local cuisine, and hidden gems.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">$<?php echo rand(30, 65); ?>/hour</span>
                                <a href="<?php echo url('tourGuide/profile/' . $i); ?>" class="btn btn-sm btn-outline-primary">View Profile</a>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <small class="text-muted">Languages: English, Spanish, French</small>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
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
            <!-- Sample Categories (to be replaced with actual data) -->
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