<!-- User Dashboard -->
<div class="container py-4">
    <h2>My Bookings</h2>
    <?php if (!empty($bookings)): ?>
        <ul class="list-group mb-4">
            <?php foreach ($bookings as $booking): ?>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Guide:</strong> <?php echo htmlspecialchars($booking->guide_name); ?><br>
                            <strong>Date:</strong> <?php echo htmlspecialchars($booking->booking_date); ?><br>
                            <strong>Status:</strong> <?php echo htmlspecialchars($booking->status); ?>
                        </div>
                        <div>
                            <a href="<?php echo url('user/booking/' . $booking->id); ?>" class="btn btn-info btn-sm">View Details</a>
                            <a href="<?php echo url('user/chat/' . $booking->id); ?>" class="btn btn-primary btn-sm">Chat with Guide</a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-info">You have no bookings yet.</div>
    <?php endif; ?>
</div>