<?php require_once VIEW_PATH . '/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><?php echo __('auth.forgot_password_title'); ?></h4>
                </div>
                <div class="card-body">
                    <p class="text-muted"><?php echo __('auth.forgot_password_desc'); ?></p>
                    
                    <!-- Display Flash Messages -->
                    <?php flash('forgot_message'); ?>
                    
                    <form action="<?php echo URL_ROOT; ?>/account/forgot-password" method="post">
                        <div class="form-group mb-3">
                            <label for="email"><?php echo __('auth.email'); ?></label>
                            <input type="email" name="email" id="email" class="form-control <?php echo (!empty($errors['email'])) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                            <span class="invalid-feedback"><?php echo $errors['email'] ?? ''; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><?php echo __('auth.send_reset_link'); ?></button>
                        </div>
                    </form>
                    
                    <div class="mt-3">
                        <a href="<?php echo URL_ROOT; ?>/account/login"><?php echo __('auth.back_to_login'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEW_PATH . '/shares/footer.php'; ?> 