    </div><!-- Close main container -->

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5><?php echo SITE_NAME; ?></h5>
                    <p class="text-white">Connecting travelers with expert local guides worldwide.</p>
                    <div class="social-links mt-3">
                        <a href="<?php echo getConfig('social_media.facebook'); ?>" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?php echo getConfig('social_media.twitter'); ?>" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="<?php echo getConfig('social_media.instagram'); ?>" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6>Guides</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo url('tourGuide/browse'); ?>" class="text-white text-decoration-none">Find a Guide</a></li>
                        <li><a href="<?php echo url('tourGuide/categories'); ?>" class="text-white text-decoration-none">Guide Categories</a></li>
                        <li><a href="<?php echo url('account/register/guide'); ?>" class="text-white text-decoration-none ">Become a Guide</a></li>
                        <li><a href="<?php echo url('guides/how-it-works'); ?>" class="text-white text-decoration-none">How It Works</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6>Company</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo url('about'); ?>" class="text-white text-decoration-none">About Us</a></li>
                        <li><a href="<?php echo url('careers'); ?>" class="text-white text-decoration-none">Careers</a></li>
                        <li><a href="<?php echo url('blog'); ?>" class="text-white text-decoration-none">Blog</a></li>
                        <li><a href="<?php echo url('contact'); ?>" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Contact Us</h6>
                    <address class="text-white">
                        <i class="fas fa-map-marker-alt me-2"></i> <?php echo getConfig('contact.address'); ?><br>
                        <i class="fas fa-phone me-2"></i> <?php echo getConfig('contact.phone'); ?><br>
                        <i class="fas fa-envelope me-2"></i> <a href="mailto:<?php echo getConfig('contact.email'); ?>" class="text-white text-decoration-none"><?php echo getConfig('contact.email'); ?></a>
                    </address>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small text-white text-decoration-none mb-0">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="list-inline small text-white mb-0">
                        <li class="list-inline-item"><a href="<?php echo url('terms'); ?>" class="text-white text-decoration-none">Terms of Service</a></li>
                        <li class="list-inline-item">|</li>
                        <li class="list-inline-item"><a href="<?php echo url('privacy'); ?>" class="text-white text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?php echo url('public/js/main.js'); ?>"></script>

    <!-- Map Floating Button -->
    <button type="button" class="btn btn-success rounded-circle shadow position-fixed"
            style="bottom: 120px; right: 20px; width: 56px; height: 56px; z-index: 1050;"
            data-bs-toggle="modal" data-bs-target="#mapModal" title="Xem bản đồ">
        <i class="fas fa-map-marked-alt fa-lg"></i>
    </button>

    <!-- Map Modal -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="mapModalLabel">Our office address</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
          </div>
          <div class="modal-body p-0">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.657600383133!2d105.78236757374515!3d21.046398484339835!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab323f3a20f1%3A0x4898724834e6958!2sHanoi%20University%20of%20Science%20and%20Technology!5e0!3m2!1sen!2s!4v1715167113971!5m2!1sen!2s"
              width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
          </div>
        </div>
      </div>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTop" class="btn btn-primary rounded-circle shadow position-fixed" 
            style="bottom: 30px; right: 30px; width: 36px; height: 36px; display: none; z-index: 1050;"
            title="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Custom JavaScript for Back to Top -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('backToTop');
        
        // Hiển thị nút khi cuộn xuống 300px
        window.onscroll = function() {
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                backToTopButton.style.display = "block";
            } else {
                backToTopButton.style.display = "none";
            }
        };
        
       // Scroll to top of page when button is clicked
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
    </script>
</body>
</html>
    
