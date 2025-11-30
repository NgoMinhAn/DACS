<?php
/**
 * Register View
 * Handles regular user registration
 */
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);">
                    <h4 class="m-0 fw-bold"><i class="fas fa-user-plus me-2"></i><?php echo __('auth.register_account'); ?></h4>
                </div>
                <div class="card-body p-4">
                    <!-- Display Flash Messages -->
                    <?php flash('register_message'); ?>
                    
                    <!-- Registration Form -->
                    <form action="<?php echo url('account/register'); ?>" method="POST">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="name" class="form-label fw-semibold"><?php echo __('auth.full_name'); ?></label>
                                <div class="input-group" style="border-radius: 10px;">
                                    <span class="input-group-text" style="background-color: #f5f5f5; border: 2px solid #e0e0e0; border-right: none;"><i class="fas fa-user text-primary"></i></span>
                                    <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" 
                                           id="name" name="name" value="<?php echo $name ?? ''; ?>" 
                                           style="border: 2px solid #e0e0e0; border-left: none; border-radius: 10px;" 
                                           placeholder="<?php echo __('auth.full_name_placeholder'); ?>" required>
                                    <?php if (isset($errors['name'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo $errors['name']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
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
                            <div class="form-text"><i class="fas fa-shield-alt me-1"></i> <?php echo __('auth.email_privacy'); ?></div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <label for="confirm_password" class="form-label fw-semibold"><?php echo __('auth.confirm_password'); ?></label>
                                <div class="input-group" style="border-radius: 10px;">
                                    <span class="input-group-text" style="background-color: #f5f5f5; border: 2px solid #e0e0e0; border-right: none;"><i class="fas fa-lock text-primary"></i></span>
                                    <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                           id="confirm_password" name="confirm_password" 
                                           style="border: 2px solid #e0e0e0; border-left: none; border-radius: 10px;" 
                                           placeholder="<?php echo __('auth.confirm_password_placeholder'); ?>" required>
                                    <?php if (isset($errors['confirm_password'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo $errors['confirm_password']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input <?php echo isset($errors['terms']) ? 'is-invalid' : ''; ?>" 
                                   id="terms" name="terms" required style="border-color: #4CAF50;">
                            <label class="form-check-label" for="terms">
                                <?php echo __('auth.agree_terms'); ?> <a href="<?php echo url('terms-of-service'); ?>" target="_blank" style="color: #4CAF50;"><?php echo __('auth.terms_of_service'); ?></a> 
                                <?php echo __('auth.and'); ?> <a href="<?php echo url('privacy-policy'); ?>" target="_blank" style="color: #4CAF50;"><?php echo __('auth.privacy_policy'); ?></a>
                            </label>
                            <?php if (isset($errors['terms'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['terms']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn w-100 text-white fw-bold py-3" style="background-color: #4CAF50; border-radius: 10px; font-size: 1.1rem; border: none;">
                            <i class="fas fa-user-plus me-2"></i><?php echo __('auth.register'); ?>
                        </button>
                    </form>
                </div>
                <div class="card-footer text-center py-4" style="background-color: #f8f9fa;">
                    <p class="mb-2"><?php echo __('auth.already_have_account'); ?> <a href="<?php echo url('account/login'); ?>" class="text-decoration-none fw-bold" style="color: #4CAF50;"><?php echo __('auth.login_now'); ?></a></p>
                    <p class="mb-0"><?php echo __('auth.or_register_guide'); ?> <a href="<?php echo url('account/register/guide'); ?>" class="text-decoration-none fw-bold" style="color: #FF9800;"><?php echo __('auth.register_as_guide'); ?></a> <?php echo __('auth.instead'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Password Toggle -->
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
});
</script>
