<!-- Booking Detail -->
<div class="container py-4">
    <h2>Booking Details</h2>
    <?php if (!empty($booking)): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Guide: <?php echo htmlspecialchars($booking->guide_name ?? ''); ?></h5>
                <p class="card-text">
                    <strong>Booking Date:</strong> <?php echo htmlspecialchars($booking->booking_date ?? ''); ?><br>
                    <strong>Status:</strong> <?php echo htmlspecialchars($booking->status ?? ''); ?><br>
                    <strong>Tour Name:</strong> <?php echo htmlspecialchars($booking->tour_name ?? ''); ?><br>
                    <strong>Start Time:</strong> <?php echo htmlspecialchars($booking->start_time ?? ''); ?><br>
                    <strong>End Time:</strong> <?php echo htmlspecialchars($booking->end_time ?? ''); ?><br>
                    <strong>Price:</strong> $<?php echo htmlspecialchars($booking->price ?? ''); ?><br>
                    <strong>Notes:</strong> <?php echo nl2br(htmlspecialchars($booking->notes ?? '')); ?>
                </p>
            </div>
        </div>
        <a href="<?php echo url('user/dashboard'); ?>" class="btn btn-secondary">Back to Dashboard</a>
    <?php else: ?>
        <div class="alert alert-danger">Booking not found.</div>
        <a href="<?php echo url('user/dashboard'); ?>" class="btn btn-secondary">Back to Dashboard</a>
    <?php endif; ?>
</div>