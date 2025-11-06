<?php flash('settings_message'); ?>

<div class="container py-5">
    <div class="row">
        <!-- Settings Navigation -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Settings</h5>
                    <div class="list-group list-group-flush">
                        <a href="<?php echo URL_ROOT; ?>/account/settings" class="list-group-item list-group-item-action d-flex align-items-center <?php echo ($section === 'general') ? 'active' : ''; ?>">
                            <i class="fas fa-cog me-2"></i> General
                        </a>
                        <a href="<?php echo URL_ROOT; ?>/account/settings/profile" class="list-group-item list-group-item-action d-flex align-items-center <?php echo ($section === 'profile') ? 'active' : ''; ?>">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                        <a href="<?php echo URL_ROOT; ?>/account/settings/password" class="list-group-item list-group-item-action d-flex align-items-center <?php echo ($section === 'password') ? 'active' : ''; ?>">
                            <i class="fas fa-lock me-2"></i> Password
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Settings Content -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-key me-2 text-primary"></i>
                        Change Password
                    </h3>
                    
                    <form action="<?php echo URL_ROOT; ?>/account/settings/password" method="POST">
                        <div class="mb-4 p-3 bg-light rounded">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control <?php echo (!empty($errors['current_password'])) ? 'is-invalid' : ''; ?>" 
                                           id="current_password" name="current_password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="invalid-feedback"><?php echo $errors['current_password'] ?? ''; ?></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    <input type="password" class="form-control <?php echo (!empty($errors['new_password'])) ? 'is-invalid' : ''; ?>" 
                                           id="new_password" name="new_password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="invalid-feedback"><?php echo $errors['new_password'] ?? ''; ?></div>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Password must be at least 6 characters long.
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    <input type="password" class="form-control <?php echo (!empty($errors['confirm_password'])) ? 'is-invalid' : ''; ?>" 
                                           id="confirm_password" name="confirm_password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="invalid-feedback"><?php echo $errors['confirm_password'] ?? ''; ?></div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Update Password
                                </button>
                            </div>
                        </div>
                    </form>

                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Password Toggle -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility for all password fields
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            
            // Toggle the type attribute
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Toggle the icon
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
});
</script> 