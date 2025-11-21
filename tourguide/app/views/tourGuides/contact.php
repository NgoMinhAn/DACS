<!-- Contact Guide Form -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Contact <?php echo htmlspecialchars($guide['name']); ?></h3>
                </div>
                <div class="card-body p-4">
                    <?php flash('contact_message'); ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><?php echo __('contact.send_message_button'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 