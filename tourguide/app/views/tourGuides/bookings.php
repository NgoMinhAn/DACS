<!-- All Bookings -->
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold mb-0">
            <i class="fas fa-calendar-check me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
            <?php echo __('guide_bookings.all_bookings'); ?>
        </h1>
        <a href="<?php echo url('guide/dashboard'); ?>" class="btn btn-outline-primary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i><?php echo __('guide_bookings.back_to_dashboard'); ?>
        </a>
    </div>

    <?php if (!empty($bookings)): ?>
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #f8f9fc 0%, #eef1f6 100%);">
                                <th class="py-3 ps-4"><?php echo __('guide_bookings.client'); ?></th>
                                <th class="py-3"><?php echo __('guide_bookings.date'); ?></th>
                                <th class="py-3"><?php echo __('guide_bookings.time'); ?></th>
                                <th class="py-3"><?php echo __('guide_bookings.status'); ?></th>
                                <th class="py-3"><?php echo __('guide_bookings.payment'); ?></th>
                                <th class="py-3"><?php echo __('guide_bookings.price'); ?></th>
                                <th class="py-3 pe-4 text-end"><?php echo __('guide_bookings.actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($booking->client_name); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($booking->client_email); ?></small>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($booking->booking_date)); ?></td>
                                    <td><?php echo date('h:i A', strtotime($booking->start_time)) . ' - ' . date('h:i A', strtotime($booking->end_time)); ?></td>
                                    <td>
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
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-2 <?php echo $booking->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                            <?php echo ucfirst($booking->payment_status); ?>
                                        </span>
                                    </td>
                                    <td>$<?php echo number_format($booking->total_price, 2); ?></td>
                                    <td class="pe-4 text-end">
                                        <a href="<?php echo url('guide/booking/' . $booking->id); ?>" class="btn btn-sm rounded-pill px-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;">
                                            <i class="fas fa-eye me-1"></i><?php echo __('guide_bookings.details'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info rounded-4 p-4 shadow">
            <i class="fas fa-info-circle me-2"></i><?php echo __('guide_bookings.no_bookings'); ?>
        </div>
    <?php endif; ?>
</div>