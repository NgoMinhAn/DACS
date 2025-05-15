<!-- Search Results Banner -->
<div class="bg-light py-4 mb-5">
    <div class="container">
        <h1 class="fs-2 fw-bold"><?php echo $title; ?></h1>
        <p class="lead mb-3">Found <?php echo $result_count; ?> guides matching your search</p>
        
        <!-- Search form to allow refining search -->
        <form action="<?php echo url('tourGuide/search'); ?>" method="GET" class="mt-3">
            <div class="input-group">
                <input type="text" class="form-control" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="Refine your search...">
                <button class="btn btn-primary" type="submit">Search Again</button>
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
                    <div class="card h-100 shadow-sm">
                        <?php if($guide->featured): ?>
                            <div class="position-absolute top-0 start-0 p-2">
                                <span class="badge bg-primary">Featured</span>
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
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No guides found matching "<?php echo htmlspecialchars($query); ?>". 
                    Try different keywords or <a href="<?php echo url('tourGuide/browse'); ?>" class="alert-link">browse all guides</a>.
                </div>
                
                <!-- Suggestion section -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Popular searches</h5>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="<?php echo url('tourGuide/search?q=city'); ?>" class="btn btn-outline-secondary btn-sm">City tours</a>
                            <a href="<?php echo url('tourGuide/search?q=food'); ?>" class="btn btn-outline-secondary btn-sm">Food experiences</a>
                            <a href="<?php echo url('tourGuide/search?q=cultural'); ?>" class="btn btn-outline-secondary btn-sm">Cultural guides</a>
                            <a href="<?php echo url('tourGuide/search?q=adventure'); ?>" class="btn btn-outline-secondary btn-sm">Adventure guides</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="text-center mt-4">
        <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-outline-primary">Browse All Guides</a>
    </div>
</div> 