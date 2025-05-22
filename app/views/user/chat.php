<div class="container py-4">
    <h2>Chat with Guide</h2>
    <div class="mb-3">
        <a href="<?php echo url('user/bookings'); ?>" class="btn btn-secondary btn-sm">Back to Dashboard</a>
    </div>
    <div class="card mb-3" style="max-height: 400px; overflow-y: auto;">
        <div class="card-body">
            <?php foreach ($messages as $msg): ?>
                <div class="mb-2">
                    <strong><?php echo $msg->sender_id == $currentUserId ? 'You' : 'Guide'; ?>:</strong>
                    <?php echo htmlspecialchars($msg->message); ?>
                    <span class="text-muted small"><?php echo $msg->created_at; ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <form method="post" class="d-flex">
        <input type="text" name="message" class="form-control me-2" placeholder="Type your message..." required>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>