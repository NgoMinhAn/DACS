    </div><!-- Close main container -->

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5><?php echo SITE_NAME; ?></h5>
                    <p class="text-muted">Connecting travelers with expert local guides worldwide.</p>
                    <div class="social-links mt-3">
                        <a href="<?php echo getConfig('social_media.facebook'); ?>" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?php echo getConfig('social_media.twitter'); ?>" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="<?php echo getConfig('social_media.instagram'); ?>" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6>Guides</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo url('tourGuide/browse'); ?>" class="text-muted">Find a Guide</a></li>
                        <li><a href="<?php echo url('tourGuide/categories'); ?>" class="text-muted">Guide Categories</a></li>
                        <li><a href="<?php echo url('account/register/guide'); ?>" class="text-muted">Become a Guide</a></li>
                        <li><a href="<?php echo url('guides/how-it-works'); ?>" class="text-muted">How It Works</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6>Company</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo url('about'); ?>" class="text-muted">About Us</a></li>
                        <li><a href="<?php echo url('careers'); ?>" class="text-muted">Careers</a></li>
                        <li><a href="<?php echo url('blog'); ?>" class="text-muted">Blog</a></li>
                        <li><a href="<?php echo url('contact'); ?>" class="text-muted">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Contact Us</h6>
                    <address class="text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i> <?php echo getConfig('contact.address'); ?><br>
                        <i class="fas fa-phone me-2"></i> <?php echo getConfig('contact.phone'); ?><br>
                        <i class="fas fa-envelope me-2"></i> <a href="mailto:<?php echo getConfig('contact.email'); ?>" class="text-muted"><?php echo getConfig('contact.email'); ?></a>
                    </address>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small text-muted mb-0">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="list-inline small text-muted mb-0">
                        <li class="list-inline-item"><a href="<?php echo url('terms'); ?>" class="text-muted">Terms of Service</a></li>
                        <li class="list-inline-item">|</li>
                        <li class="list-inline-item"><a href="<?php echo url('privacy'); ?>" class="text-muted">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?php echo url('public/js/main.js'); ?>"></script>
</body>
</html>
