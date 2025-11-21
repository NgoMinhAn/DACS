<!-- Search Results Banner -->
<div class="bg-light py-4 mb-5">
    <div class="container">
        <h1 class="fs-2 fw-bold"><?php echo $title; ?></h1>
        <p class="lead mb-3"><?php echo __('search.results', ['count' => $result_count]); ?></p>
        
        <!-- Search form to allow refining search -->
        <form action="<?php echo url('tourGuide/search'); ?>" method="GET" class="mt-3">
            <div class="input-group">
                <input type="text" class="form-control" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="<?php echo __('search.placeholder'); ?>">
                <button class="btn btn-primary" type="submit"><?php echo __('search.button'); ?></button>
            </div>
        </form>
    </div>
</div>

<!-- Search Results Section -->
<div class="container mb-5">
    <?php if(isset($_SESSION['search_message'])): ?>
        <div class="<?php echo $_SESSION['search_message_class']; ?> mb-4">
            <?php echo $_SESSION['search_message']; ?>
            <?php unset($_SESSION['search_message']); ?>
            <?php unset($_SESSION['search_message_class']); ?>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <?php if(!empty($guides)): ?>
            <?php foreach($guides as $guide): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <a href="<?php echo url('tourGuide/profile/' . $guide->guide_id); ?>" class="text-decoration-none">
                        <div class="card h-100 shadow-sm guide-card">
                            <?php if($guide->featured): ?>
                                <div class="position-absolute top-0 start-0 p-2" style="z-index: 2;">
                                    <span class="badge bg-primary">Featured</span>
                                </div>
                            <?php endif; ?>
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
                                <p class="card-text text-dark"><?php echo htmlspecialchars(substr($guide->bio ?? 'Expert local guide', 0, 60)) . '...'; ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary fw-bold">$<?php echo number_format($guide->hourly_rate, 2); ?>/hour</span>
                                    <span class="btn btn-sm btn-outline-primary"><?php echo __('buttons.view_profile'); ?></span>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                <small class="text-muted"><?php echo $guide->specialties ?? 'Various specialties'; ?></small>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> <?php echo __('search.no_results', ['q' => htmlspecialchars($query)]); ?> 
                    <?php echo __('search.try_or'); ?> <a href="<?php echo url('tourGuide/browse'); ?>" class="alert-link"><?php echo __('nav.find_guide'); ?></a>.
                </div>
                
                <!-- Suggestion section -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo __('search.popular'); ?></h5>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="<?php echo url('tourGuide/search?q=city'); ?>" class="btn btn-outline-secondary btn-sm"><?php echo __('search.city_tours'); ?></a>
                            <a href="<?php echo url('tourGuide/search?q=food'); ?>" class="btn btn-outline-secondary btn-sm"><?php echo __('search.food_experiences'); ?></a>
                            <a href="<?php echo url('tourGuide/search?q=cultural'); ?>" class="btn btn-outline-secondary btn-sm"><?php echo __('search.cultural_guides'); ?></a>
                            <a href="<?php echo url('tourGuide/search?q=adventure'); ?>" class="btn btn-outline-secondary btn-sm"><?php echo __('search.adventure_guides'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="text-center mt-4">
        <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-outline-primary"><?php echo __('nav.find_guide'); ?></a>
    </div>
</div> 