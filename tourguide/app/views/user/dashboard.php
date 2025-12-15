<!-- User Dashboard -->
<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg scroll-animate fade-up">
                <div class="card-header border-0 pb-0" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-tachometer-alt me-2"></i><?php echo __('user.dashboard.title'); ?>
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="mb-4"><?php echo __('user.dashboard.my_bookings'); ?></h4>

                            <?php if (empty($bookings)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted"><?php echo __('user.dashboard.no_bookings'); ?></h5>
                                    <p class="text-muted"><?php echo __('user.dashboard.no_bookings_desc'); ?></p>
                                    <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i><?php echo __('user.dashboard.find_guide'); ?>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($bookings as $booking): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100 border">
                                                <div class="card-body">
                                                    <h6 class="card-title">
                                                        <a href="<?php echo url('user/booking/' . $booking->booking_id); ?>" class="text-decoration-none">
                                                            <?php echo __('user.dashboard.booking_with'); ?> <?php echo htmlspecialchars($booking->guide_name); ?>
                                                        </a>
                                                    </h6>
                                                    <p class="card-text small text-muted mb-2">
                                                        <i class="fas fa-calendar me-1"></i><?php echo date('M j, Y', strtotime($booking->date)); ?> at <?php echo $booking->start_time; ?>
                                                    </p>
                                                    <p class="card-text small text-muted mb-2">
                                                        <i class="fas fa-clock me-1"></i><?php echo $booking->duration_hours; ?> <?php echo __('user.dashboard.hours'); ?>
                                                    </p>
                                                    <p class="card-text small mb-2">
                                                        <span class="badge bg-<?php echo $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'secondary'); ?>">
                                                            <?php echo ucfirst($booking->status); ?>
                                                        </span>
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="fw-bold">$<?php echo number_format($booking->total_price, 2); ?></span>
                                                        <a href="<?php echo url('user/chat/' . $booking->booking_id); ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-comments me-1"></i><?php echo __('user.dashboard.chat'); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i><?php echo __('user.dashboard.quick_actions'); ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-search me-2"></i><?php echo __('user.dashboard.find_guide'); ?>
                                        </a>
                                        <a href="<?php echo url('user/bookings'); ?>" class="btn btn-outline-secondary">
                                            <i class="fas fa-list me-2"></i><?php echo __('user.dashboard.view_all_bookings'); ?>
                                        </a>
                                        <a href="<?php echo url('user/accountsetting/settings'); ?>" class="btn btn-outline-secondary">
                                            <i class="fas fa-cog me-2"></i><?php echo __('user.dashboard.account_settings'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-question-circle me-2"></i><?php echo __('user.dashboard.help'); ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="small text-muted mb-2"><?php echo __('user.dashboard.help_desc'); ?></p>
                                    <a href="<?php echo url('contact'); ?>" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-envelope me-1"></i><?php echo __('user.dashboard.contact_support'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>