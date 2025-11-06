<div class="container py-5 flex-grow-1" style="max-width: 1000px;">
    <div class="row">
        <!-- Settings Navigation -->
        <div class="col-md-3">
            <?php include __DIR__ . '/settings_nav.php'; ?>
        </div>
        <!-- Settings Content -->
        <div class="col-md-9">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4">
                    <h3 class="card-title mb-4 fw-bold">
                        <i class="fas fa-cog me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        General Settings
                    </h3>

                    <!-- Contact Requests Button -->
                    <?php if ($_SESSION['user_type'] === 'guide'): ?>
                        <a href="<?php echo url('guide/contacts'); ?>" class="btn btn-outline-primary btn-lg w-100 d-flex align-items-center justify-content-center gap-2 mb-3 rounded-pill">
                            <i class="fa fa-envelope-open-text"></i>
                            <span>View contact requests from users</span>
                        </a>
                        <p class="text-muted mb-4" style="font-size: 0.95rem;">See all messages sent to you by users who want to contact you as a guide.</p>
                    <?php endif; ?>

                    <!-- Email -->
                    <div class="mb-4 p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                <i class="fas fa-envelope fa-lg text-white"></i>
                            </div>
                            <h5 class="mb-0 fw-bold">Email Address</h5>
                        </div>
                        <form method="post" action="">
                            <div class="row">
                                <div class="col-md-8 mb-2">
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="<?php echo htmlspecialchars($guide->email ?? ''); ?>" required>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <button type="submit" class="btn btn-outline-primary w-100 rounded-pill">
                                        <i class="fas fa-save me-2"></i>Update Email
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Password Change -->
                    <div class="mb-4 p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                <i class="fas fa-shield-alt fa-lg text-white"></i>
                            </div>
                            <h5 class="mb-0 fw-bold">Account Security</h5>
                        </div>
                        <p class="text-muted mb-3">Manage your account security settings and password.</p>
                        <a href="<?php echo url('guide/password-settings'); ?>" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-key me-2"></i>
                            Change Password
                        </a>
                    </div>

                    <!-- Delete Account -->
                    <div class="mt-5 p-4 rounded-4 border border-danger" style="background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 me-3 bg-danger">
                                <i class="fas fa-exclamation-triangle fa-lg text-white"></i>
                            </div>
                            <h5 class="text-danger mb-0 fw-bold">Danger Zone</h5>
                        </div>
                        <p class="text-muted mb-3">Once you delete your account, there is no going back. Please be certain.</p>
                        <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
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
                <form action="<?php echo url('tourGuide/delete-account'); ?>" method="POST" id="deleteAccountForm">
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