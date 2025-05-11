<?php
/**
 * Forgot Password View
 * Allows users to request a password reset link
 */
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="m-0"><?php echo $title; ?></h4>
                </div>
                <div class="card-body">
                    <p class="mb-4">Enter your email address below and we'll send you a link to reset your password.</p>
                    
                    <!-- Display Flash Messages -->
                    <?php flash('forgot_message'); ?>
                    
                    <!-- Forgot Password Form -->
                    <form action="<?php echo url('account/forgot-password'); ?>" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                       id="email" name="email" value="<?php echo $email ?? ''; ?>" required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['email']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text">
                                We'll send a password reset link to this email address if it's registered in our system.
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Send Reset Link</button>
                            <a href="<?php echo url('account/login'); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 