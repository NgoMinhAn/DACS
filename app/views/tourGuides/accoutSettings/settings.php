<div class="container py-5 flex-grow-1" style="max-width: 1000px;">
    <div class="row">
        <!-- Settings Navigation -->
        <div class="col-md-3">
            <?php include __DIR__ . '/settings_nav.php'; ?>
        </div>
        <!-- Settings Content -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-cog me-2 text-primary"></i>
                        General Settings
                    </h3>

                    <!-- Email -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <h5 class="mb-0">Email Address</h5>
                        </div>
                        <form method="post" action="">
                            <div class="row">
                                <div class="col-md-8 mb-2">
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="<?php echo htmlspecialchars($guide->email ?? ''); ?>" required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-save me-2"></i>Update Email
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Password Change -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-shield-alt text-primary me-2"></i>
                            <h5 class="mb-0">Account Security</h5>
                        </div>
                        <p class="text-muted mb-3">Manage your account security settings and password.</p>
                        <a href="<?php echo url('guide/password-settings'); ?>" class="btn btn-outline-primary">
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
                <form action="<?php echo url('guide/delete-account'); ?>" method="POST" id="deleteAccountForm">
                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Please enter your password to confirm:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="delete_password" name="password" required>
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