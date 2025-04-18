<?php
/**
 * Register View
 * Handles regular user registration
 */
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="m-0"><?php echo $title; ?></h4>
                </div>
                <div class="card-body">
                    <!-- Display Flash Messages -->
                    <?php flash('register_message'); ?>
                    
                    <!-- Registration Form -->
                    <form action="<?php echo url('account/register'); ?>" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" 
                                           id="name" name="name" value="<?php echo $name ?? ''; ?>" required>
                                    <?php if (isset($errors['name'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo $errors['name']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
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
                            <div class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                                           id="password" name="password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
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
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                           id="confirm_password" name="confirm_password" required>
                                    <?php if (isset($errors['confirm_password'])): ?>
                                        <div class="invalid-feedback">
                                            <?php echo $errors['confirm_password']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input <?php echo isset($errors['terms']) ? 'is-invalid' : ''; ?>" 
                                   id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="<?php echo url('terms-of-service'); ?>" target="_blank">Terms of Service</a> 
                                and <a href="<?php echo url('privacy-policy'); ?>" target="_blank">Privacy Policy</a>
                            </label>
                            <?php if (isset($errors['terms'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['terms']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Register Account</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Already have an account? <a href="<?php echo url('account/login'); ?>" class="text-decoration-none">Login here</a></p>
                    <p class="mt-2 mb-0">Or <a href="<?php echo url('account/register/guide'); ?>" class="text-decoration-none">register as a tour guide</a> instead.</p>
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
