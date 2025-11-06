<!-- In your bookingsList.php -->
<?php if($booking->status == 'pending'): ?>
    <a href="<?php echo url('tourGuide/acceptBooking/' . $booking->id); ?>" class="btn btn-success btn-sm">Accept</a>
    <a href="<?php echo url('tourGuide/declineBooking/' . $booking->id); ?>" class="btn btn-danger btn-sm">Decline</a>
<?php endif; ?>
<a href="<?php echo url('tourGuide/chat/' . $booking->id); ?>" class="btn btn-primary btn-sm">Chat</a>