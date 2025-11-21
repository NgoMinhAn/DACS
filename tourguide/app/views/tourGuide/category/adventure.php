<!-- Adventure Category Header -->
<div class="bg-primary text-white py-4 mb-5">
    <div class="container">
                <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="fw-bold"><?php echo htmlspecialchars($category_info->name ?? 'Adventure Guides'); ?></h1>
                <p class="lead"><?php echo htmlspecialchars($category_info->description ?? __('category.contact_prompt')); ?></p>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="<?php echo url('public/img/category-adventure.jpg'); ?>" alt="Adventure Tours" class="img-fluid rounded-3 shadow-lg" style="height: 250px; object-fit: cover; width: 100%;">
            </div>
        </div>
    </div>
</div>

<!-- Adventure Guides Listing -->
<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="fw-bold">Adventure Specialists</h2>
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
            <!-- Adventure Guides from Database -->
            <?php if(!empty($category_guides)): ?>
                <?php foreach($category_guides as $guide): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="<?php echo url('tourGuide/profile/' . $guide->guide_id); ?>" class="text-decoration-none">
                            <div class="card h-100 shadow-sm guide-card">
                                <div class="card-img-wrapper position-relative overflow-hidden">
                                    <img src="<?php echo url('assets/images/profiles/' . ($guide->profile_image ?? 'default.jpg')); ?>" 
                                         class="card-img-top guide-card-img" alt="<?php echo htmlspecialchars($guide->name); ?>" 
                                         style="height: 200px; object-fit: cover;">
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0 text-dark"><?php echo htmlspecialchars($guide->name); ?></h5>
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-star"></i> <?php echo number_format($guide->avg_rating, 1); ?>
                                        </span>
                                    </div>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($guide->location); ?>
                                    </p>
                                    <p class="card-text text-dark"><?php echo htmlspecialchars(substr($guide->bio ?? 'Adventure specialist offering thrilling experiences', 0, 100)) . '...'; ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold">$<?php echo number_format($guide->hourly_rate, 2); ?>/hour</span>
                                        <span class="btn btn-sm btn-outline-primary"><?php echo __('buttons.view_profile'); ?></span>
                                    </div>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="small text-muted">Adventure specialties: 
                                        <span class="fw-medium"><?php echo $guide->specialties ?? 'Hiking, Mountain Climbing, Rafting'; ?></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i> No adventure guides available at the moment. Please check back soon or try another category!
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

<!-- Popular Adventure Activities -->
<section class="mb-5 py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold">Popular Adventure Activities</h2>
                <p class="text-muted">Discover thrilling experiences led by our expert guides</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo url('public/img/adventure-hiking.jpg'); ?>" class="card-img-top" alt="Hiking" style="height: 180px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Hiking & Trekking</h5>
                        <p class="card-text text-muted">Explore scenic trails with expert hiking guides</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo url('public/img/adventure-climbing.jpg'); ?>" class="card-img-top" alt="Climbing" style="height: 180px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Rock Climbing</h5>
                        <p class="card-text text-muted">Scale heights with certified climbing instructors</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo url('public/img/adventure-rafting.jpg'); ?>" class="card-img-top" alt="Rafting" style="height: 180px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title">White Water Rafting</h5>
                        <p class="card-text text-muted">Navigate rapids with experienced river guides</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo url('public/img/adventure-diving.jpg'); ?>" class="card-img-top" alt="Diving" style="height: 180px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title">Scuba Diving</h5>
                        <p class="card-text text-muted">Explore underwater worlds with diving professionals</p>
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
                            <h3 class="fw-bold"><?php echo __('category.cta_title'); ?></h3>
                            <p class="lead mb-0"><?php echo __('category.cta_desc'); ?></p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="<?php echo url('contact'); ?>" class="btn btn-light btn-lg"><?php echo __('buttons.contact_us'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
