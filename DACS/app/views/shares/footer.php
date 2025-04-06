    </div><!-- Close the container div -->

    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-map-marked-alt mr-2"></i>LocalGuides</h5>
                    <p class="text-muted">Connect with experienced local guides for personalized tours anywhere in the world.</p>
                    <div class="social-links">
                        <a href="#" class="text-white mr-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white mr-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white mr-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>For Tourists</h5>
                    <ul class="list-unstyled">
                        <li><a href="/DACS/guides" class="text-light">Find Guides</a></li>
                        <li><a href="/DACS/bookings" class="text-light">My Bookings</a></li>
                        <li><a href="/DACS/reviews" class="text-light">Write Reviews</a></li>
                        <li><a href="/DACS/faq" class="text-light">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>For Guides</h5>
                    <ul class="list-unstyled">
                        <li><a href="/DACS/guides/create" class="text-light">Join as Guide</a></li>
                        <li><a href="/DACS/guide/bookings" class="text-light">Manage Bookings</a></li>
                        <li><a href="/DACS/guide/earnings" class="text-light">Earnings</a></li>
                        <li><a href="/DACS/guide/profile" class="text-light">Guide Profile</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt mr-2"></i> 123 Tour Street, City, Country</li>
                        <li><i class="fas fa-phone mr-2"></i> +1 (555) 123-4567</li>
                        <li><i class="fas fa-envelope mr-2"></i> info@localguides.com</li>
                    </ul>
                    <form class="mt-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Enter your email">
                            <div class="input-group-append">
                                <button class="btn btn-outline-light" type="button">Subscribe</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="bg-light">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?= date('Y') ?> LocalGuides. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="/DACS/terms" class="text-light">Terms of Service</a></li>
                        <li class="list-inline-item"><a href="/DACS/privacy" class="text-light">Privacy Policy</a></li>
                        <li class="list-inline-item"><a href="/DACS/cookies" class="text-light">Cookies</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Enable Bootstrap tooltips
        $(function () {
            $(['data-toggle="tooltip"']).tooltip();
        });
        
        // Auto-hide alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $(".alert").alert('close');
            }, 5000);
        });
    </script>
</body>
</html>