<?php
/**
 * Login View
 * Handles user authentication for all account types (Users, Tour Guides, Admins)
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
                    <!-- Display Flash Messages -->
                    <?php flash('login_message'); ?>
                    
                    <!-- Login Form -->
                    <form action="<?php echo url('account/login'); ?>" method="POST">
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
                        </div>
                        
                        <div class="mb-3">
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
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="remember_me" name="remember_me">
                                    <label class="form-check-label" for="remember_me">
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <a href="<?php echo url('account/forgot-password'); ?>" class="text-decoration-none">Forgot Password?</a>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Sign In</button>
                    </form>

                    <div class="mt-4 text-center">
                        <p class="text-muted">Or sign in with</p>
                        <a href="<?php echo url('account/google-login'); ?>" class="btn btn-outline-danger">
                            <i class="fab fa-google"></i> Sign in with Google
                        </a>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Don't have an account?</p>
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-2 mt-2">
                        <a href="<?php echo url('account/register'); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus"></i> Register as User
                        </a>
                        <a href="<?php echo url('account/register/guide'); ?>" class="btn btn-outline-success">
                            <i class="fas fa-map-marked-alt"></i> Become a Tour Guide
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
