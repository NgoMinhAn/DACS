<!-- Adventure Category Hero Header -->
<div class="category-hero-header position-relative overflow-hidden mb-5">
    <?php if (!empty($category_info->image)): ?>
        <div class="category-hero-bg" style="background-image: url('<?php echo url('public/img/categories/' . htmlspecialchars($category_info->image)); ?>');"></div>
    <?php else: ?>
        <div class="category-hero-bg" style="background-image: url('<?php echo url('public/img/category-adventure.jpg'); ?>');"></div>
    <?php endif; ?>
    <div class="category-hero-overlay"></div>
    <div class="container position-relative">
        <div class="row align-items-center min-vh-30">
            <div class="col-lg-8 mx-auto text-center scroll-animate fade-up">
                <div class="category-hero-content">
                    <h1 class="display-3 fw-bold text-white mb-4"><?php echo htmlspecialchars($category_info->name ?? 'Adventure Guides'); ?></h1>
                    <p class="lead text-white mb-4 fs-5"><?php echo htmlspecialchars($category_info->description ?? 'Connect with guides who specialize in thrilling and unforgettable adventure experiences'); ?></p>
                    <div class="category-hero-badge">
                        <span class="badge bg-white bg-opacity-25 text-white px-4 py-2 fs-6">
                            Thrilling Experiences
                        </span>
                    </div>
                </div>
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
            <?php 
            $hasGuides = isset($category_guides) && (
                (is_array($category_guides) && count($category_guides) > 0) ||
                (is_object($category_guides) && count((array)$category_guides) > 0)
            );
            if($hasGuides): 
            ?>
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
                                        <span class="btn btn-sm btn-outline-primary">View Profile</span>
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
                <!-- Empty State: No Guides Available -->
                <div class="col-12">
                    <div class="empty-state-container scroll-animate fade-up">
                        <div class="text-center py-5">
                            <div class="empty-state-icon mb-4">
                                <i class="fas fa-mountain" style="font-size: 5rem; color: #dee2e6;"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Không có hướng dẫn viên nào</h3>
                            <p class="text-muted mb-4">Hiện tại danh mục này chưa có hướng dẫn viên nào. Vui lòng thử lại sau hoặc khám phá các danh mục khác.</p>
                            <div class="d-flex gap-3 justify-content-center flex-wrap">
                                <a href="<?php echo url('tourGuide/category/categories'); ?>" class="btn btn-primary">
                                    <i class="fas fa-th-large me-2"></i>Xem tất cả danh mục
                                </a>
                                <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-2"></i>Tìm kiếm hướng dẫn viên
                                </a>
                            </div>
                        </div>
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
                <div class="p-5 rounded-4 shadow-lg scroll-animate fade-up" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-4 mb-lg-0">
                            <h3 class="fw-bold text-white mb-3">Need a Customized Adventure Experience?</h3>
                            <p class="lead mb-0 text-white">Contact us to connect with specialized adventure guides for your perfect trip</p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="<?php echo url('contact'); ?>" class="btn btn-light btn-lg shadow px-4">
                                <i class="fas fa-envelope me-2"></i>Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
