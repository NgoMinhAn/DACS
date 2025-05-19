<!-- Guide Reviews Page -->
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>My Reviews</h2>
                    <p class="text-muted">Manage and view all reviews from your clients</p>
                </div>
                <a href="<?php echo url('guide/dashboard'); ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

            <!-- Reviews Summary -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title mb-3">Overall Rating</h4>
                            <div class="d-flex align-items-center">
                                <div class="display-4 me-3"><?php echo number_format($guide->avg_rating, 1); ?></div>
                                <div>
                                    <div class="mb-2">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= round($guide->avg_rating)): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="text-muted">
                                        Based on <?php echo $total_reviews; ?> <?php echo $total_reviews == 1 ? 'review' : 'reviews'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title mb-3">Rating Distribution</h4>
                            <?php
                            $ratingCounts = array_fill(1, 5, 0);
                            foreach ($reviews as $review) {
                                $ratingCounts[$review->rating]++;
                            }
                            for ($i = 5; $i >= 1; $i--):
                                $percentage = $total_reviews > 0 ? ($ratingCounts[$i] / $total_reviews) * 100 : 0;
                            ?>
                                <div class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2" style="width: 60px;">
                                            <?php echo $i; ?> <i class="fas fa-star text-warning"></i>
                                        </div>
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar bg-warning" role="progressbar" 
                                                 style="width: <?php echo $percentage; ?>%" 
                                                 aria-valuenow="<?php echo $percentage; ?>" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100"></div>
                                        </div>
                                        <div class="ms-2" style="width: 40px;">
                                            <?php echo $ratingCounts[$i]; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">All Reviews</h4>
                    <div class="text-muted">
                        Showing <?php echo count($reviews); ?> of <?php echo $total_reviews; ?> reviews
                    </div>
                </div>
                <div class="card-body">
                    <?php if(!empty($reviews)): ?>
                        <?php foreach($reviews as $review): ?>
                            <div class="mb-4 pb-4 border-bottom">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <span class="fw-bold"><?php echo htmlspecialchars($review->name); ?></span>
                                        <span class="text-muted ms-2"><?php echo date('M d, Y', strtotime($review->created_at)); ?></span>
                                    </div>
                                    <div>
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= $review->rating): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="mb-0"><?php echo htmlspecialchars($review->review_text); ?></p>
                                <?php if(!empty($review->booking_id)): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Booking #<?php echo $review->booking_id; ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i> You don't have any reviews yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 