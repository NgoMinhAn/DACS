<?php
<div class="container py-4">
    <h2>Booking Details</h2>
    <?php if ($booking): ?>
        <ul class="list-group mb-3">
            <li class="list-group-item"><strong>Client:</strong> <?php echo htmlspecialchars($booking->client_name); ?></li>
            <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($booking->client_email); ?></li>
            <li class="list-group-item"><strong>Date:</strong> <?php echo htmlspecialchars($booking->booking_date); ?></li>
            <li class="list-group-item"><strong>Time:</strong> <?php echo htmlspecialchars($booking->start_time); ?> - <?php echo htmlspecialchars($booking->end_time); ?></li>
            <li class="list-group-item"><strong>Status:</strong> <?php echo htmlspecialchars($booking->status); ?></li>
            <li class="list-group-item"><strong>Price:</strong> $<?php echo number_format($booking->total_price, 2); ?></li>
            <li class="list-group-item"><strong>Special Requests:</strong> <?php echo htmlspecialchars($booking->special_requests); ?></li>
        </ul>
        <!-- Add Accept/Decline and Chat buttons here if needed -->
    <?php else: ?>
        <div class="alert alert-danger">Booking not found.</div>
    <?php endif; ?>
</div>