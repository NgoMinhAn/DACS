<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm mt-5">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="fas fa-sign-in-alt mr-2"></i>Login to Your Account</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="/login">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control" id="email" name="email" value="<?= isset($_SESSION['old_input']['email']) ? htmlspecialchars($_SESSION['old_input']['email']) : '' ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                            <a href="/forgot-password" class="float-right">Forgot password?</a>
                        </div>
                        
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3">
                    <p class="mb-0">Don't have an account? <a href="/register">Sign up now</a></p>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted mb-2">Or login with</p>
                <div class="social-login">
                    <a href="/auth/google" class="btn btn-light border mr-2"><i class="fab fa-google text-danger"></i> Google</a>
                    <a href="/auth/facebook" class="btn btn-light border"><i class="fab fa-facebook-f text-primary"></i> Facebook</a>
                </div>
            </div>
        </div>
    </div>
</div> 