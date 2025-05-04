<!-- City Category Header -->
<div class="bg-primary text-white py-4 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="fw-bold">City Guides</h1>
                <p class="lead">Discover urban landscapes and hidden gems with our expert city tour guides</p>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="<?php echo url('public/img/category-city.jpg'); ?>" alt="City Tours" class="img-fluid rounded-3 shadow-lg" style="height: 250px; object-fit: cover; width: 100%;">
            </div>
        </div>
    </div>
</div>

<!-- City Guides Listing -->
<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="fw-bold">Urban Exploration Specialists</h2>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Sort By
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                            <li><a class="dropdown-item" href="?sort=rating">Top Rated</a></li>
                            <li><a class="dropdown-item" href="?sort=price_low">Price: Low to High</a></li>
                            <li><a class="dropdown-item" href="?sort=price_high">Price: High to Low</a></li>
                            <li><a class="dropdown-item" href="?sort=experience">Most Experienced</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- City Guides from Database -->
            <?php if(!empty($category_guides)): ?>
                <?php foreach($category_guides as $guide): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
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
                                <p class="card-text"><?php echo htmlspecialchars(substr($guide->bio ?? 'Expert city guide with in-depth knowledge of urban areas', 0, 100)) . '...'; ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary fw-bold">$<?php echo number_format($guide->hourly_rate, 2); ?>/hour</span>
                                    <a href="<?php echo url('tourGuide/profile/' . $guide->guide_id); ?>" class="btn btn-sm btn-outline-primary">View Profile</a>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="small text-muted">City specialties: 
                                    <span class="fw-medium"><?php echo $guide->specialties ?? 'Architecture Tours, City Walks, Hidden Gems'; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i> No city guides available at the moment. Please check back soon or try another category!
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination if needed -->
        <?php if(!empty($pagination)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <nav aria-label="Guide pagination">
                        <ul class="pagination justify-content-center">
                            <?php echo $pagination; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Popular City Tour Types -->
<section class="mb-5 py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Popular City Tour Experiences</h2>
                <p class="text-muted">Explore the urban landscape with specialized city tours</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo url('public/img/city-architecture.jpg'); ?>" class="card-img-top" alt="Architecture Tours" style="height: 180px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Architecture Tours</h5>
                        <p class="card-text text-muted">Discover iconic buildings and urban design with expert guides</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo url('public/img/city-food.jpg'); ?>" class="card-img-top" alt="Food Tours" style="height: 180px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Urban Food Tours</h5>
                        <p class="card-text text-muted">Sample local cuisine at hidden eateries and popular spots</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo url('public/img/city-history.jpg'); ?>" class="card-img-top" alt="History Tours" style="height: 180px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Historical Walking Tours</h5>
                        <p class="card-text text-muted">Step back in time with fascinating historical narratives</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo url('public/img/city-night.jpg'); ?>" class="card-img-top" alt="Night Tours" style="height: 180px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Evening & Nightlife Tours</h5>
                        <p class="card-text text-muted">Experience the city's vibrant nightlife with local experts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bg-primary text-white p-5 rounded-3">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-4 mb-lg-0">
                            <h3 class="fw-bold">Looking for a Specialized City Experience?</h3>
                            <p class="lead mb-0">Tell us what you're interested in and we'll match you with the perfect city guide</p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="<?php echo url('contact'); ?>" class="btn btn-light btn-lg">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
