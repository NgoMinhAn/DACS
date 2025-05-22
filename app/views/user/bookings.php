<div class="container my-5">
    <h2 class="mb-4">Tour list</h2>
    <?php if (empty($bookings)): ?>
        <div class="alert alert-info">You have not booked any tours yet.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Tour guide</th>
                        <th>Date of booking</th>
                        <th>Time</th>
                        <th>Meeting place</th>
                        <th>Number</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
    <td>
        <img src="<?php echo url('public/uploads/' . ($booking->guide_image ?? 'default.jpg')); ?>" alt="Guide" width="40" height="40" class="rounded-circle me-2">
        <?php echo htmlspecialchars($booking->guide_name); ?>
    </td>
    <td><?php echo date('d/m/Y', strtotime($booking->booking_date)); ?></td>
    <td><?php echo htmlspecialchars($booking->start_time . ' - ' . $booking->end_time); ?></td>
    <td><?php echo htmlspecialchars($booking->meeting_location); ?></td>
    <td><?php echo (int)$booking->number_of_people; ?></td>
    <td>
        <?php
            switch ($booking->status) {
                case 'pending': echo '<span class="badge bg-warning text-dark">pending</span>'; break;
                case 'confirmed': echo '<span class="badge bg-success">confirmed</span>'; break;
                case 'completed': echo '<span class="badge bg-primary">completed</span>'; break;
                case 'cancelled': echo '<span class="badge bg-danger">cancelled</span>'; break;
                default: echo htmlspecialchars($booking->status);
            }
        ?>
    </td>
    <td>
        <a href="<?php echo url('user/chat/' . $booking->id); ?>" class="btn btn-sm btn-primary">
            Chat with Guide
        </a>
    </td>
</tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>