<?php require_once VIEW_PATH . '/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Đặt lại mật khẩu</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Vui lòng nhập mật khẩu mới của bạn.</p>
                    
                    <form action="<?php echo URL_ROOT; ?>/account/reset-password/<?php echo $token; ?>" method="post">
                        <div class="form-group mb-3">
                            <label for="password">Mật khẩu mới</label>
                            <input type="password" name="password" id="password" class="form-control <?php echo (!empty($errors['password'])) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $errors['password'] ?? ''; ?></span>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="confirm_password">Xác nhận mật khẩu</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control <?php echo (!empty($errors['confirm_password'])) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $errors['confirm_password'] ?? ''; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
                        </div>
                    </form>
                    
                    <div class="mt-3">
                        <a href="<?php echo URL_ROOT; ?>/account/login">Quay lại đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once VIEW_PATH . '/shares/footer.php'; ?> 