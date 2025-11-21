<!-- Category Hero Header -->
<div class="category-hero-header position-relative overflow-hidden mb-5">
    <?php if (!empty($category_info->image)): ?>
        <div class="category-hero-bg" style="background-image: url('<?php echo url('public/img/categories/' . htmlspecialchars($category_info->image)); ?>');"></div>
    <?php else: ?>
        <div class="category-hero-bg" style="background-image: url('<?php echo url('public/img/category-default.jpg'); ?>');"></div>
    <?php endif; ?>
    <div class="category-hero-overlay"></div>
    <div class="container position-relative">
        <div class="row align-items-center min-vh-30">
            <div class="col-lg-8 mx-auto text-center scroll-animate fade-up">
                <div class="category-hero-content">
                    <h1 class="display-3 fw-bold text-white mb-4"><?php echo htmlspecialchars($category_info->name ?? $title); ?></h1>
                    <p class="lead text-white mb-4 fs-5"><?php echo htmlspecialchars($category_info->description ?? 'Connect with expert guides specializing in this area'); ?></p>
                    <div class="category-hero-badge">
                        <span class="badge bg-white bg-opacity-25 text-white px-4 py-2 fs-6">
                            Expert Guides
                        </span>
                    </div>
            </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Guides -->
<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="fw-bold"><?php echo htmlspecialchars(str_replace(' Tour Guides', ' Specialists', $title)); ?></h2>
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
        
        <!-- Guides in this Category -->
        <div class="row">
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
                            <img src="<?php echo url('public/img/guide-' . $guide->guide_id . '.jpg'); ?>" 
                                         class="card-img-top guide-card-img" alt="<?php echo htmlspecialchars($guide->name); ?>" 
                                 onerror="this.src='<?php echo url('public/img/default-profile.jpg'); ?>'"
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
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($guide->location ?? 'Local area'); ?>
                                </p>
                                    <p class="card-text text-dark"><?php echo htmlspecialchars(substr($guide->bio ?? 'Experienced guide offering specialized tours', 0, 100)) . '...'; ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary fw-bold">$<?php echo number_format($guide->hourly_rate, 2); ?>/hour</span>
                                        <span class="btn btn-sm btn-outline-primary"><?php echo __('buttons.view_profile'); ?></span>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="small text-muted">Specialties: 
                                    <span class="fw-medium"><?php echo htmlspecialchars($guide->specialties); ?></span>
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
                                <i class="fas fa-compass" style="font-size: 5rem; color: #dee2e6;"></i>
                            </div>
                            <h3 class="fw-bold mb-3"><?php echo __('category.none_title'); ?></h3>
                            <p class="text-muted mb-4"><?php echo __('category.none_desc'); ?></p>
                            <div class="d-flex gap-3 justify-content-center flex-wrap">
                                <a href="<?php echo url('tourGuide/category/categories'); ?>" class="btn btn-primary">
                                    <i class="fas fa-th-large me-2"></i><?php echo __('category.view_all'); ?>
                                </a>
                                <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-2"></i><?php echo __('category.search_guides'); ?>
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

<!-- Back to Categories -->
<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <a href="<?php echo url('tourGuide/category/categories'); ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i> Back to All Categories
                </a>
            </div>
        </div>
    </div>
</section> 