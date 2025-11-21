<?php flash('settings_message'); ?>

<div class="container py-5">
    <div class="row">
        <!-- Settings Navigation -->
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">
                        <i class="fas fa-cog me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        Settings
                    </h5>
                    <div class="list-group list-group-flush">
                            <a href="<?php echo url('account/settings'); ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 mb-2 <?php echo ($section === 'general') ? 'active' : ''; ?>" 
                           style="<?php echo ($section === 'general') ? 'background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white; border: none;' : ''; ?>">
                            <i class="fas fa-cog me-2"></i> General
                        </a>
                        <a href="<?php echo url('account/settings/profile'); ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 mb-2 <?php echo ($section === 'profile') ? 'active' : ''; ?>"
                           style="<?php echo ($section === 'profile') ? 'background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white; border: none;' : ''; ?>">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                            <a href="<?php echo url('account/settings/password'); ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 <?php echo ($section === 'password') ? 'active' : ''; ?>"
                           style="<?php echo ($section === 'password') ? 'background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white; border: none;' : ''; ?>">
                            <i class="fas fa-lock me-2"></i> Password
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="col-md-9">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4">
                    <h3 class="card-title mb-4 fw-bold">
                        <i class="fas fa-cog me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        General Settings
                    </h3>
                    
                    <!-- Account Type -->
                    <div class="mb-4 p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                <i class="fas fa-user-tag fa-lg text-white"></i>
                            </div>
                            <h5 class="mb-0 fw-bold"><?php echo __('settings.account_type') ?? 'Account Type'; ?></h5>
                        </div>
                        <p class="text-muted mb-2">
                            Your current account type is: 
                            <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;">
                                <?php echo ucfirst($user->user_type); ?>
                            </span>
                        </p>
                        <?php if ($user->user_type === 'user'): ?>
                            <p class="text-muted mb-2"><?php echo __('settings.become_prompt') ?? 'Interested in sharing your knowledge and guiding others?'; ?></p>
                            <a href="<?php echo url('account/becomeguide?step=1'); ?>" class="btn rounded-pill px-4 text-white" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);">
                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                <?php echo __('nav.become_guide'); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Account Status -->
                    <div class="mb-4 p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                <i class="fas fa-check-circle fa-lg text-white"></i>
                            </div>
                            <h5 class="mb-0 fw-bold"><?php echo __('settings.account_status') ?? 'Account Status'; ?></h5>
                        </div>
                        <p class="text-muted mb-2">
                            Your account is currently 
                            <span class="badge bg-<?php echo ($user->status === 'active') ? 'success' : 'warning'; ?>">
                                <?php echo ucfirst($user->status); ?>
                            </span>
                        </p>
                    </div>

                    <!-- Account Security -->
                    <div class="mb-4 p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                <i class="fas fa-shield-alt fa-lg text-white"></i>
                            </div>
                            <h5 class="mb-0 fw-bold"><?php echo __('settings.account_security') ?? 'Account Security'; ?></h5>
                        </div>
                        <p class="text-muted mb-3">Manage your account security settings and connected devices</p>
                        <a href="<?php echo url('account/settings/password'); ?>" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-key me-2"></i>
                            Change Password
                        </a>
                    </div>

                    <!-- Language -->
                    <div class="mb-4 p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 me-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                <i class="fas fa-language fa-lg text-white"></i>
                            </div>
                            <h5 class="mb-0 fw-bold"><?php echo __('settings.language'); ?></h5>
                        </div>
                        <form action="<?php echo url('account/settings'); ?>" method="POST" class="row g-2 align-items-center">
                            <div class="col-sm-6">
                                <select name="language" class="form-select">
                                    <?php $current = getLocale(); ?>
                                    <option value="en" <?php echo $current==='en'?'selected':''; ?>>English</option>
                                    <option value="vi" <?php echo $current==='vi'?'selected':''; ?>>Tiếng Việt</option>
                                </select>
                            </div>
                                <div class="col-sm-6 text-sm-start">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i><?php echo __('settings.save'); ?>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Delete Account -->
                    <div class="mt-5 p-4 rounded-4 border border-danger" style="background: linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-3 me-3 bg-danger">
                                <i class="fas fa-exclamation-triangle fa-lg text-white"></i>
                            </div>
                            <h5 class="text-danger mb-0 fw-bold"><?php echo __('common.danger_zone') ?? 'Danger Zone'; ?></h5>
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
                    <?php echo __('settings.delete_warning') ?? 'This action cannot be undone. All your data will be permanently deleted.'; ?>
                </div>
                <form action="<?php echo URL_ROOT; ?>/account/delete" method="POST" id="deleteAccountForm">
                    <div class="mb-3">
                        <label for="password" class="form-label"><?php echo __('settings.confirm_password') ?? 'Please enter your password to confirm:'; ?></label>
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
                    <?php echo __('buttons.cancel') ?? 'Cancel'; ?>
                </button>
                <button type="submit" form="deleteAccountForm" class="btn btn-danger">
                    <i class="fas fa-trash-alt me-2"></i>
                    <?php echo __('buttons.delete_account') ?? 'Delete Account'; ?>
                </button>
            </div>
        </div>
    </div>
</div> 