<?php
/**
 * Login View
 * Handles user authentication for all account types (Users, Tour Guides, Admins)
 */
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);">
                    <h4 class="m-0 fw-bold"><i class="fas fa-sign-in-alt me-2"></i><?php echo __('auth.login'); ?></h4>
                </div>
                <div class="card-body p-4">
                    <!-- Display Flash Messages -->
                    <?php flash('login_message'); ?>
                    
                    <!-- Login Form -->
                    <form action="<?php echo url('account/login'); ?>" method="POST">
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold"><?php echo __('auth.email'); ?></label>
                            <div class="input-group" style="border-radius: 10px;">
                                <span class="input-group-text" style="background-color: #f5f5f5; border: 2px solid #e0e0e0; border-right: none;"><i class="fas fa-envelope text-primary"></i></span>
                                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                                       id="email" name="email" value="<?php echo $email ?? ''; ?>" 
                                       style="border: 2px solid #e0e0e0; border-left: none; border-radius: 10px;" 
                                       placeholder="<?php echo __('auth.email_placeholder'); ?>" required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['email']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold"><?php echo __('auth.password'); ?></label>
                            <div class="input-group" style="border-radius: 10px;">
                                <span class="input-group-text" style="background-color: #f5f5f5; border: 2px solid #e0e0e0; border-right: none;"><i class="fas fa-lock text-primary"></i></span>
                                <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                       id="password" name="password" 
                                       style="border: 2px solid #e0e0e0; border-left: none; border-right: none;" 
                                       placeholder="<?php echo __('auth.password_placeholder'); ?>" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1" style="border: 2px solid #e0e0e0; border-left: none; border-radius: 0 10px 10px 0; background-color: #f5f5f5;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if (isset($errors['password'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['password']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="remember_me" name="remember_me" style="border-color: #4CAF50;">
                                    <label class="form-check-label" for="remember_me">
                                        <?php echo __('auth.remember_me'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <a href="<?php echo url('account/forgot-password'); ?>" class="text-decoration-none" style="color: #4CAF50;"><?php echo __('auth.forgot_password'); ?></a>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn w-100 text-white fw-bold py-3" style="background-color: #4CAF50; border-radius: 10px; font-size: 1.1rem; border: none;">
                            <i class="fas fa-sign-in-alt me-2"></i><?php echo __('auth.login'); ?>
                        </button>
                    </form>

                    <div class="mt-4 text-center">
                        <div class="position-relative mb-3">
                            <hr>
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted"><?php echo __('auth.or'); ?></span>
                        </div>
                        <a href="<?php echo url('account/google-login'); ?>" class="btn btn-outline-danger w-100" style="border-radius: 10px;">
                            <i class="fab fa-google me-2"></i> <?php echo __('auth.login_with_google'); ?>
                        </a>
                    </div>
                </div>
                <div class="card-footer text-center py-4" style="background-color: #f8f9fa;">
                    <p class="mb-3"><?php echo __('auth.no_account'); ?></p>
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-2">
                        <a href="<?php echo url('account/register'); ?>" class="btn btn-outline-primary" style="border-radius: 10px;">
                            <i class="fas fa-user-plus me-2"></i> <?php echo __('auth.register_as_user'); ?>
                        </a>
                        <a href="<?php echo url('account/register/guide'); ?>" class="btn text-white" style="background-color: #FF9800; border-radius: 10px; border: none;">
                            <i class="fas fa-map-marked-alt me-2"></i> <?php echo __('auth.become_guide'); ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Optional: Quick Login Boxes for Demo Purposes -->
            <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'development'): ?>
                <div class="card mt-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="m-0">Quick Demo Login</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <button class="btn btn-outline-secondary w-100 demo-login" data-email="sarah@example.com" data-password="password">
                                    <i class="fas fa-user"></i> User Account
                                </button>
                            </div>
                            <div class="col-md-4 mb-2">
                                <button class="btn btn-outline-secondary w-100 demo-login" data-email="john@example.com" data-password="password">
                                    <i class="fas fa-map-marked-alt"></i> Guide Account
                                </button>
                            </div>
                            <div class="col-md-4 mb-2">
                                <button class="btn btn-outline-secondary w-100 demo-login" data-email="admin@example.com" data-password="password">
                                    <i class="fas fa-user-shield"></i> Admin Account
                                </button>
                            </div>
                        </div>
                        <p class="text-muted small text-center mt-2 mb-0">
                            These buttons are visible only in development mode for demonstration purposes.
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript for Password Toggle and Demo Login -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.querySelector('.toggle-password');
    const password = document.querySelector('#password');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle the icon
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // Demo login buttons (development only)
    const demoButtons = document.querySelectorAll('.demo-login');
    demoButtons.forEach(button => {
        button.addEventListener('click', function() {
            const email = this.getAttribute('data-email');
            const password = this.getAttribute('data-password');
            
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        });
    });
});
</script>
