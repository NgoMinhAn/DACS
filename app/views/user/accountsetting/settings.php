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

        <!-- Settings Content -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-cog me-2 text-primary"></i>
                        General Settings
                    </h3>
                    
                    <!-- Account Type -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user-tag text-primary me-2"></i>
                            <h5 class="mb-0">Account Type</h5>
                        </div>
                        <p class="text-muted mb-2">
                            Your current account type is: 
                            <span class="badge bg-primary"><?php echo ucfirst($user->user_type); ?></span>
                        </p>
                        <?php if ($user->user_type === 'user'): ?>
                            <p class="text-muted mb-2">Interested in sharing your knowledge and guiding others?</p>
                            <a href="<?php echo URL_ROOT; ?>/account/becomeguide" class="btn btn-success">
                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                Become a Guide
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Account Status -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-primary me-2"></i>
                            <h5 class="mb-0">Account Status</h5>
                        </div>
                        <p class="text-muted mb-2">
                            Your account is currently 
                            <span class="badge bg-<?php echo ($user->status === 'active') ? 'success' : 'warning'; ?>">
                                <?php echo ucfirst($user->status); ?>
                            </span>
                        </p>
                    </div>

                    <!-- Account Security -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-shield-alt text-primary me-2"></i>
                            <h5 class="mb-0">Account Security</h5>
                        </div>
                        <p class="text-muted mb-3">Manage your account security settings and connected devices</p>
                        <a href="<?php echo URL_ROOT; ?>/account/settings/password" class="btn btn-outline-primary">
                            <i class="fas fa-key me-2"></i>
                            Change Password
                        </a>
                    </div>

                    <!-- Delete Account -->
                    <div class="mt-5 p-3 bg-danger bg-opacity-10 rounded border border-danger">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                            <h5 class="text-danger mb-0">Danger Zone</h5>
                        </div>
                        <p class="text-muted mb-3">Once you delete your account, there is no going back. Please be certain.</p>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash-alt me-2"></i>
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Delete Account
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    This action cannot be undone. All your data will be permanently deleted.
                </div>
                <form action="<?php echo URL_ROOT; ?>/account/delete" method="POST" id="deleteAccountForm">
                    <div class="mb-3">
                        <label for="password" class="form-label">Please enter your password to confirm:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Cancel
                </button>
                <button type="submit" form="deleteAccountForm" class="btn btn-danger">
                    <i class="fas fa-trash-alt me-2"></i>
                    Delete Account
                </button>
            </div>
        </div>
    </div>
</div> 