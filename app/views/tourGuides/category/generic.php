<!-- Category Header -->
<div class="bg-primary text-white py-4 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="fw-bold"><?php echo htmlspecialchars($category_info->name ?? $title); ?></h1>
                <p class="lead"><?php echo htmlspecialchars($category_info->description ?? 'Connect with expert guides specializing in this area'); ?></p>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <?php if (!empty($category_info->image)): ?>
                    <img src="<?php echo url('public/img/categories/' . htmlspecialchars($category_info->image)); ?>" 
                         alt="<?php echo htmlspecialchars($category_info->name ?? $title); ?>" 
                         class="img-fluid rounded-3 shadow-lg" 
                         style="height: 250px; object-fit: cover; width: 100%;"
                         onerror="this.src='<?php echo url('public/img/category-default.jpg'); ?>'">
                <?php else: ?>
                    <img src="<?php echo url('public/img/category-default.jpg'); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="img-fluid rounded-3 shadow-lg" style="height: 250px; object-fit: cover; width: 100%;">
                <?php endif; ?>
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
            <?php if(!empty($category_guides)): ?>
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
                                        <span class="btn btn-sm btn-outline-primary">View Profile</span>
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
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i> No guides available for this specialty at the moment. Please check back soon or try another category!
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