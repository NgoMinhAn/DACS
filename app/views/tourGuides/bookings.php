<!-- All Bookings -->
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>All Bookings</h2>
        <a href="<?php echo url('guide/dashboard'); ?>" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <?php if (!empty($bookings)): ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <div class="fw-bold"><?php echo htmlspecialchars($booking->client_name); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($booking->client_email); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($booking->booking_date)); ?></td>
                                    <td><?php echo date('h:i A', strtotime($booking->start_time)) . ' - ' . date('h:i A', strtotime($booking->end_time)); ?></td>
                                    <td>
                                        <span class="badge <?php
                                            switch ($booking->status) {
                                                case 'confirmed':
                                                    echo 'bg-success';
                                                    break;
                                                case 'pending':
                                                    echo 'bg-warning';
                                                    break;
                                                case 'cancelled':
                                                    echo 'bg-danger';
                                                    break;
                                                case 'completed':
                                                    echo 'bg-info';
                                                    break;
                                                default:
                                                    echo 'bg-secondary';
                                            }
                                            ?>">
                                            <?php echo ucfirst($booking->status); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $booking->payment_status === 'paid' ? 'bg-success' : 'bg-warning'; ?>">
                                            <?php echo ucfirst($booking->payment_status); ?>
                                        </span>
                                    </td>
                                    <td>$<?php echo number_format($booking->total_price, 2); ?></td>
                                    <td>
                                        <a href="<?php echo url('guide/booking/' . $booking->id); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Details
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
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>You don't have any bookings yet.
        </div>
    <?php endif; ?>
</div> 