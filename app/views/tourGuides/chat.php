<?php
<!-- In app/views/tourGuides/chat.php -->
<div class="chat-box">
    <?php foreach($messages as $msg): ?>
        <div><strong><?php echo $msg->sender_id == $currentUserId ? 'You' : 'Them'; ?>:</strong> <?php echo htmlspecialchars($msg->message); ?></div>
    <?php endforeach; ?>
    <form method="post" action="<?php echo url('tourGuide/sendMessage/' . $bookingId); ?>">
        <input type="text" name="message" class="form-control" required>
        <button type="submit" class="btn btn-primary mt-2">Send</button>
    </form>
</div>