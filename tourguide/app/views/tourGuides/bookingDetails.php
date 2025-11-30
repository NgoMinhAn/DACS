<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold mb-0">
            <i class="fas fa-file-invoice me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
            <?php echo __('guide_bookings.booking_details'); ?>
        </h1>
        <a href="<?php echo url('guide/bookings'); ?>" class="btn btn-outline-primary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i><?php echo __('guide_bookings.back_to_bookings'); ?>
        </a>
    </div>

    <?php if ($booking): ?>
        <div class="card border-0 shadow-lg rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-2"><strong><?php echo __('guide_bookings.client'); ?>:</strong> <?php echo htmlspecialchars($booking->client_name); ?></div>
                        <div class="mb-2"><strong><?php echo __('guide_bookings.email'); ?>:</strong> <?php echo htmlspecialchars($booking->client_email); ?></div>
                        <div class="mb-2"><strong><?php echo __('guide_bookings.special_requests'); ?>:</strong> <?php echo !is_null($booking->special_requests) ? htmlspecialchars($booking->special_requests) : '—'; ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2"><strong><?php echo __('guide_bookings.date'); ?>:</strong> <?php echo date('M d, Y', strtotime($booking->booking_date)); ?></div>
                        <div class="mb-2"><strong><?php echo __('guide_bookings.time'); ?>:</strong> <?php echo date('h:i A', strtotime($booking->start_time)); ?> - <?php echo date('h:i A', strtotime($booking->end_time)); ?></div>
                        <div class="mb-2">
                            <strong><?php echo __('guide_bookings.status'); ?>:</strong>
                            <?php
                                $statusClass = 'bg-secondary';
                                switch ($booking->status) {
                                    case 'confirmed': $statusClass = 'bg-success'; break;
                                    case 'pending': $statusClass = 'bg-warning text-dark'; break;
                                    case 'cancelled': $statusClass = 'bg-danger'; break;
                                    case 'completed': $statusClass = 'bg-primary'; break;
                                }
                            ?>
                            <span class="badge rounded-pill px-3 py-2 <?php echo $statusClass; ?>"><?php echo ucfirst($booking->status); ?></span>
                        </div>
                        <div><strong><?php echo __('guide_bookings.price'); ?>:</strong> $<?php echo number_format($booking->total_price, 2); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <?php if ($booking->status === 'pending'): ?>
                <form method="post" action="<?php echo url('guide/acceptBooking/' . $booking->id); ?>">
                    <button type="submit" class="btn btn-success rounded-pill px-4"><i class="fas fa-check me-2"></i><?php echo __('buttons.accept_booking'); ?></button>
                </form>
                <form method="post" action="<?php echo url('guide/declineBooking/' . $booking->id); ?>">
                    <button type="submit" class="btn btn-danger rounded-pill px-4"><i class="fas fa-times me-2"></i><?php echo __('buttons.decline_booking'); ?></button>
                </form>
            <?php endif; ?>

            <a href="<?php echo url('guide/chat/' . $booking->id); ?>" class="btn rounded-pill px-4 text-white" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                <i class="fas fa-comments me-2"></i><?php echo __('guide_bookings.chat_with_client'); ?>
            </a>
        </div>

    <?php else: ?>
        <div class="alert alert-danger rounded-4 p-4 shadow"><?php echo __('guide_bookings.booking_not_found'); ?></div>
    <?php endif; ?>
</div>