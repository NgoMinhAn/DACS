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
            <li class="list-group-item"><strong>Special Requests:</strong> <?php echo !is_null($booking->special_requests) ? htmlspecialchars($booking->special_requests) : ''; ?></li>
        </ul>

        <!-- Accept Button (only if not already accepted/declined) -->
        <?php if ($booking->status === 'pending'): ?>
            <form method="post" action="<?php echo url('guide/acceptBooking/' . $booking->id); ?>" class="d-inline">
                <button type="submit" class="btn btn-success">Accept Booking</button>
            </form>
            <form method="post" action="<?php echo url('guide/declineBooking/' . $booking->id); ?>" class="d-inline ms-2">
                <button type="submit" class="btn btn-danger">Decline Booking</button>
            </form>
        <?php endif; ?>

        <!-- Chat Button -->
        <a href="<?php echo url('guide/chat/' . $booking->id); ?>" class="btn btn-primary ms-2">Chat with Client</a>

    <?php else: ?>
        <div class="alert alert-danger">Booking not found.</div>
    <?php endif; ?>
</div>