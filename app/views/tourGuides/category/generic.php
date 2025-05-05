<!-- Category Header -->
<div class="bg-primary text-white py-4 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="fw-bold"><?php echo htmlspecialchars($title); ?></h1>
                <p class="lead">Connect with expert guides specializing in this area</p>
            </div>
        </div>
    </div>
</div>

<!-- Category Guides -->
<section class="mb-5">
    <div class="container">
        <!-- Guides in this Category -->
        <div class="row">
            <?php if(!empty($category_guides)): ?>
                <?php foreach($category_guides as $guide): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-transparent">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo url('public/img/guide-' . $guide->guide_id . '.jpg'); ?>" 
                                         class="rounded-circle me-3" width="50" height="50" alt="Guide"
                                         onerror="this.src='<?php echo url('public/img/default-profile.jpg'); ?>'">
                                    <div>
                                        <h5 class="mb-0"><?php echo htmlspecialchars($guide->name); ?></h5>
                                        <div class="text-warning">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= round($guide->avg_rating)): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php elseif($i <= $guide->avg_rating + 0.5): ?>
                                                    <i class="fas fa-star-half-alt"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <span class="text-muted ms-2">(<?php echo $guide->total_reviews ?? 0; ?>)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><?php echo htmlspecialchars(substr($guide->bio, 0, 150) . '...'); ?></p>
                                <div class="mb-2">
                                    <span class="badge bg-light text-dark me-1">
                                        <i class="fas fa-language text-primary me-1"></i>
                                        <?php echo htmlspecialchars($guide->languages ?? 'English'); ?>
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <span class="badge bg-light text-dark me-1">
                                        <i class="fas fa-tag text-primary me-1"></i>
                                        <?php echo htmlspecialchars($guide->specialties ?? 'Local Tours'); ?>
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary fw-bold">$<?php echo number_format($guide->hourly_rate, 2); ?>/hour</span>
                                    <a href="<?php echo url('tourGuide/profile/' . $guide->guide_id); ?>" class="btn btn-sm btn-outline-primary">View Profile</a>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="small text-muted">Specialties: 
                                    <span class="fw-medium"><?php echo htmlspecialchars($guide->specialties); ?></span>
                                </div>
                            </div>
                        </div>
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