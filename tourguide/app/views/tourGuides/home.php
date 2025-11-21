<!-- Hero Section with Carousel -->
<!-- 
    IMAGE ADD INSTRUCTIONS:
    1. Add images to folder: public/img/
    2. Add a new indicator button (update the data-bs-slide-to number)
    3. Add a new carousel-item div with the path to your image
-->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="false">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
    </div>
    <div class="carousel-inner">
        <!-- Slide 1: Hà Nội -->
        <div class="carousel-item active">
            <div class="hero-banner position-relative" style="background: url('<?php echo url('public/img/anh-ha-noi.jpg'); ?>') center center/cover no-repeat; min-height: 600px;"></div>
        </div>
        <!-- Slide 2: Sài Gòn -->
        <div class="carousel-item">
            <div class="hero-banner position-relative" style="background: url('<?php echo url('public/img/saigon-cathedral.jpeg'); ?>') center center/cover no-repeat; min-height: 600px;"></div>
        </div>
        <!-- Slide 3: Ảnh mới 1 -->
        <div class="carousel-item">
            <div class="hero-banner position-relative" style="background: url('<?php echo url('public/img/andy-holmes-0LJCEORiYg8-unsplash.jpg'); ?>') center center/cover no-repeat; min-height: 600px;"></div>
        </div>
        <!-- Slide 4: Ảnh mới 2 -->
        <div class="carousel-item">
            <div class="hero-banner position-relative" style="background: url('<?php echo url('public/img/photo-1528127269322-539801943592.jpg'); ?>') center center/cover no-repeat; min-height: 600px;"></div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" style="z-index: 10;">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="z-index: 10;">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
    
    <!-- Overlay and Search Bar -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background:rgba(0,0,0,0.3); z-index: 1;"></div>
    <div class="container h-100 position-absolute top-0 start-0 w-100" style="z-index:2; min-height: 600px;">
        <div class="row align-items-end h-100" style="padding-bottom: 80px;">
            <div class="col-12">
                <!-- Search Bar Overlay -->
                <div class="search-overlay bg-light rounded-4 p-4 shadow-lg" style="background-color: #ffffff !important; border: 1px solid #e0e0e0;">
                    <form action="<?php echo url('tourGuide/search'); ?>" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label text-dark fw-semibold mb-2">
                                    <i class="fas fa-search text-primary me-2"></i><?php echo __('search.label'); ?>
                                </label>
                                <input type="text" class="form-control form-control-lg" name="q" placeholder="<?php echo __('search.placeholder'); ?>" style="border-radius: 10px; border: 2px solid #e0e0e0;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-semibold mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i><?php echo __('search.location'); ?>
                                </label>
                                <select class="form-select form-select-lg" name="location" style="border-radius: 10px; border: 2px solid #e0e0e0;">
                                    <option value="" selected><?php echo __('search.all_locations'); ?></option>
                                    <option value="hanoi">Hà Nội</option>
                                    <option value="hochiminh">Hồ Chí Minh</option>
                                    <option value="danang">Đà Nẵng</option>
                                    <option value="hue">Huế</option>
                                    <option value="halong">Hạ Long</option>
                                    <option value="sapa">Sapa</option>
                                    <option value="nhatrang">Nha Trang</option>
                                    <option value="dalat">Đà Lạt</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                    <button class="btn btn-lg w-100 text-white fw-bold" type="submit" style="background-color: #4CAF50; border-radius: 10px; min-height: 58px; border: none;">
                                    <i class="fas fa-search me-2"></i><?php echo __('search.button'); ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Title Section -->
<section class="py-5 scroll-animate fade-up" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
    <div class="container">
        <div class="row">
                <div class="col-12 text-center text-white">
                <h1 class="display-4 fw-bold mb-3"><?php echo __('hero.title'); ?></h1>
                <p class="lead mb-0 fs-5"><?php echo __('hero.desc'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Guides Section -->
<section class="mb-5 py-5" style="background-color: var(--warm-cream);">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5 scroll-animate fade-up">
                <h2 class="fw-bold display-5 mb-3">
                    <i class="fas fa-star me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                    <?php echo __('home.top_rated_title'); ?>
                </h2>
                <p class="text-muted lead"><?php echo __('home.top_rated_desc'); ?></p>
            </div>
        </div>
        
        <div class="row">
            <!-- Featured Guides from Database -->
            <?php if(!empty($featured_guides)): ?>
                <?php foreach($featured_guides as $index => $guide): ?>
                    <div class="col-md-6 col-lg-3 mb-4 scroll-animate fade-up <?php echo 'delay-' . min($index + 1, 5); ?>">
                        <a href="<?php echo url('tourGuide/profile/' . $guide->guide_id); ?>" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-lg guide-card">
                                <?php if($index < 3): ?>
                                    <div class="position-absolute top-0 start-0 p-2" style="z-index: 2;">
                                        <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; font-size: 0.9rem;">
                                            <i class="fas fa-crown me-1"></i>Top <?php echo $index + 1; ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <div class="card-img-wrapper position-relative overflow-hidden">
                                    <img src="<?php echo url('assets/images/profiles/' . ($guide->profile_image ?? 'default.jpg')); ?>" 
                                         class="card-img-top guide-card-img" alt="<?php echo htmlspecialchars($guide->name); ?>" 
                                         style="height: 200px; object-fit: cover;">
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0 text-dark fw-bold"><?php echo htmlspecialchars($guide->name); ?></h5>
                                        <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;">
                                            <i class="fas fa-star"></i> <?php echo number_format($guide->avg_rating, 1); ?>
                                        </span>
                                    </div>
                                    <p class="card-text text-muted small mb-2">
                                        <i class="fas fa-map-marker-alt me-2 text-primary"></i> <?php echo htmlspecialchars($guide->location); ?>
                                    </p>
                                    <p class="card-text text-dark mb-3"><?php echo htmlspecialchars(substr($guide->bio ?? 'Expert local guide', 0, 60)) . '...'; ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold" style="color: #4CAF50; font-size: 1.1rem;">$<?php echo number_format($guide->hourly_rate, 2); ?>/hour</span>
                                        <span class="btn btn-sm rounded-pill px-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;"><?php echo __('buttons.view_profile'); ?></span>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-0">
                                    <small class="text-muted">
                                        <i class="fas fa-tags me-1"></i><?php echo $guide->specialties ?? 'Various specialties'; ?>
                                    </small>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info rounded-4 p-4">
                        <i class="fas fa-info-circle me-2"></i> No featured guides available at the moment. Check back soon!
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-lg rounded-pill px-5 shadow text-white" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                <i class="fas fa-th-large me-2"></i><?php echo __('nav.find_guide'); ?>
            </a>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="mb-5 py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5 scroll-animate fade-up">
                <h2 class="fw-bold display-5 mb-3">
                    <i class="fas fa-cog me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                    How It Works
                </h2>
                <p class="text-muted lead">Easy steps to connect with your perfect guide</p>
            </div>
        </div>
        
        <div class="row" id="how-it-works-steps">
            <div class="col-md-4 mb-4 mb-md-0 scroll-animate fade-left delay-1">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-4 p-4 rounded-circle d-inline-block" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-search fa-3x text-white"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">1. Find Your Guide</h5>
                        <p class="card-text text-muted">Browse profiles of experienced local guides and find one that matches your interests and needs.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0 scroll-animate fade-up delay-2">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-4 p-4 rounded-circle d-inline-block" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-comments fa-3x text-white"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">2. Connect Directly</h5>
                        <p class="card-text text-muted">Message your chosen guide to discuss your interests, dates, and personalize your experience.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 scroll-animate fade-right delay-3">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-4 p-4 rounded-circle d-inline-block" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-map-marked-alt fa-3x text-white"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">3. Enjoy Your Experience</h5>
                        <p class="card-text text-muted">Meet your guide and enjoy a personalized experience crafted just for you.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-none" id="how-it-works-more">
            <div class="col-md-4 mb-4 mb-md-0 mt-4 scroll-animate fade-up delay-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-4 p-4 rounded-circle d-inline-block" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-lock fa-3x text-white"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">4. Secure Your Booking</h5>
                        <p class="card-text text-muted">Confirm your guide and make a secure payment through our trusted platform.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0 mt-4 scroll-animate fade-up delay-5">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-4 p-4 rounded-circle d-inline-block" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-lightbulb fa-3x text-white"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">5. Get Travel Tips</h5>
                        <p class="card-text text-muted">Receive custom tips, local insights, and preparation advice tailored to your destination.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-4 scroll-animate fade-up delay-6">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-4 p-4 rounded-circle d-inline-block" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-share-alt fa-3x text-white"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">6. Share Your Journey</h5>
                        <p class="card-text text-muted">Leave a review and share your experience to help future travelers make informed choices.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="#" class="btn btn-outline-primary rounded-pill px-4" id="how-it-works-learn-more">
                <i class="fas fa-chevron-down me-2"></i>Learn More
            </a>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.getElementById('how-it-works-learn-more');
            var more = document.getElementById('how-it-works-more');
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                more.classList.remove('d-none');
                btn.style.display = 'none';
            });
        });
        </script>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 mb-5" style="background-color: var(--warm-cream);">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5 scroll-animate fade-up">
                <h2 class="fw-bold display-5 mb-3">
                    <i class="fas fa-quote-left me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                    What Our Travelers Say
                </h2>
                <p class="text-muted lead">Real experiences from people who connected with their perfect guides</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4 scroll-animate fade-up delay-1">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-body p-4">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text lead" style="line-height: 1.8;">"Our guide Maria was incredible! She knew the city inside out and took us to places we would have never found on our own. The direct connection made the experience so much more personal."</p>
                        <div class="d-flex align-items-center mt-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <img src="<?php echo url('public/img/testimonial1.jpg'); ?>" class="rounded-circle" width="50" height="50" alt="Testimonial" style="object-fit: cover;">
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">John Smith</h6>
                                <small class="text-muted">New York, USA</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4 mb-4 scroll-animate fade-up delay-2">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-body p-4">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="card-text lead" style="line-height: 1.8;">"The best part of our trip was having Carlos as our guide. He was knowledgeable, fun, and adapted the tour to our interests. Being able to work directly with him made all the difference."</p>
                        <div class="d-flex align-items-center mt-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <img src="<?php echo url('public/img/testimonial2.jpg'); ?>" class="rounded-circle" width="50" height="50" alt="Testimonial" style="object-fit: cover;">
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Emily Johnson</h6>
                                <small class="text-muted">London, UK</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4 mb-4 scroll-animate fade-up delay-3">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-body p-4">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="card-text lead" style="line-height: 1.8;">"We hired Akiko as our guide in Kyoto and it was the highlight of our Japan trip. Her local knowledge and personal connections gave us insights no tour company could provide."</p>
                        <div class="d-flex align-items-center mt-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <img src="<?php echo url('public/img/testimonial3.jpg'); ?>" class="rounded-circle" width="50" height="50" alt="Testimonial" style="object-fit: cover;">
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Michael Chen</h6>
                                <small class="text-muted">Toronto, Canada</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section - Become a Guide -->
<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 scroll-animate scale-in">
                <div class="p-5 rounded-4 shadow-lg" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-4 mb-lg-0">
                            <h2 class="fw-bold text-white mb-3">
                                <i class="fas fa-user-tie me-2"></i>Are You a Tour Guide?
                            </h2>
                            <p class="lead mb-0 text-white">Join our platform and connect directly with travelers seeking your expertise. No middleman, higher earnings, and full control of your services.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="<?php echo url('account/register/guide'); ?>" class="btn btn-light btn-lg rounded-pill px-4 shadow">
                                <i class="fas fa-rocket me-2"></i><?php echo __('nav.become_guide'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<style>
    .input-group .form-control::placeholder {
        color: #888 !important;
        opacity: 1;
    }
    .btn-success:hover, .btn-success:focus {
        background: #218838 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Bootstrap to be fully loaded
    if (typeof bootstrap !== 'undefined') {
        // Initialize carousel with auto-play
        var carouselElement = document.querySelector('#heroCarousel');
        if (carouselElement) {
            var carousel = new bootstrap.Carousel(carouselElement, {
                interval: 4000,
                ride: 'carousel',
                wrap: true
            });
            
            // Ensure carousel continues cycling
            carouselElement.addEventListener('mouseenter', function() {
                carousel.pause();
            });
            carouselElement.addEventListener('mouseleave', function() {
                carousel.cycle();
            });
            
            // Ensure navigation buttons work
            var prevButton = carouselElement.querySelector('.carousel-control-prev');
            var nextButton = carouselElement.querySelector('.carousel-control-next');
            
            if (prevButton) {
                prevButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    carousel.prev();
                });
            }
            
            if (nextButton) {
                nextButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    carousel.next();
                });
            }
        }
    } else {
        console.error('Bootstrap is not loaded');
    }
});
</script>