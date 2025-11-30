<!-- About Us Hero Section -->
<div class="category-hero-header position-relative overflow-hidden mb-5">
    <div class="category-hero-bg" style="background-image: url('<?php echo url('public/img/about-hero.jpg'); ?>'); background-position: center;"></div>
    <div class="category-hero-overlay"></div>
    <div class="container position-relative">
        <div class="row align-items-center min-vh-30">
            <div class="col-lg-8 mx-auto text-center scroll-animate fade-up">
                <div class="category-hero-content">
                    <h1 class="display-3 fw-bold text-white mb-4"><?php echo __('about.hero_title'); ?></h1>
                    <p class="lead text-white mb-4 fs-5"><?php echo __('about.hero_desc'); ?></p>
                    <div class="category-hero-badge">
                        <span class="badge bg-white bg-opacity-25 text-white px-4 py-2 fs-6">
                            <?php echo __('about.hero_badge'); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Our Story Section -->
<div class="container mb-5">
    <div class="row">
        <div class="col-lg-8 mx-auto text-center scroll-animate fade-up">
            <h2 class="mb-4"><?php echo __('about.our_story_title'); ?></h2>
            <p class="lead"><?php echo str_replace(':site_name', SITE_NAME, __('about.our_story_lead')); ?></p>
            <p><?php echo __('about.our_story_text'); ?></p>
        </div>
    </div>
</div>

<!-- Our Mission Section -->
<div class="py-5 mb-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center scroll-animate scale-in">
                <div class="text-white">
                    <h2 class="mb-4 text-white"><?php echo __('about.our_mission_title'); ?></h2>
                    <p class="lead text-white"><?php echo __('about.our_mission_lead'); ?></p>
                    <p class="text-white"><?php echo __('about.our_mission_text'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- What Makes Us Different Section -->
<div class="container mb-5">
    <h2 class="text-center mb-5 scroll-animate fade-up"><?php echo __('about.what_makes_different_title'); ?></h2>
    <div class="row g-4">
        <div class="col-md-4 scroll-animate fade-up delay-1">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3" style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-check fa-2x text-white"></i>
                    </div>
                    <h5 class="card-title"><?php echo __('about.verified_guides_title'); ?></h5>
                    <p class="card-text"><?php echo __('about.verified_guides_text'); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 scroll-animate fade-up delay-2">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3" style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-hands-helping fa-2x text-white"></i>
                    </div>
                    <h5 class="card-title"><?php echo __('about.community_title'); ?></h5>
                    <p class="card-text"><?php echo __('about.community_text'); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 scroll-animate fade-up delay-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="feature-icon mb-3" style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-route fa-2x text-white"></i>
                    </div>
                    <h5 class="card-title"><?php echo __('about.customized_title'); ?></h5>
                    <p class="card-text"><?php echo __('about.customized_text'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Team Section -->
<div class="py-5 mb-5" style="background-color: var(--warm-cream);">
    <div class="container">
        <h2 class="text-center mb-5 scroll-animate fade-up"><?php echo __('about.team_title'); ?></h2>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 scroll-animate fade-up delay-1">
                <div class="card border-0 shadow-sm h-100 team-card">
                    <img src="<?php echo url('public/images/team/ceo.jpg'); ?>" class="card-img-top" alt="CEO" style="height: 250px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-0">Ngo Minh An</h5>
                        <p class="text-muted">Main Developer</p>
                        <p class="small text-muted">2280618417</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 scroll-animate fade-up delay-2">
                <div class="card border-0 shadow-sm h-100 team-card">
                    <img src="<?php echo url('public/images/team/cto.jpg'); ?>" class="card-img-top" alt="CTO" style="height: 250px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-0">Phan Tri Tai</h5>
                        <p class="text-muted">N/A</p>
                        <p class="small text-muted">2280618417</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 scroll-animate fade-up delay-3">
                <div class="card border-0 shadow-sm h-100 team-card">
                    <img src="<?php echo url('public/images/team/coo.jpg'); ?>" class="card-img-top" alt="COO" style="height: 250px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title mb-0">Nguyen Thanh Khoa</h5>
                        <p class="text-muted">N/A</p>
                        <p class="small text-muted">2280601532</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="container mb-5">
    <h2 class="text-center mb-5 scroll-animate fade-up"><?php echo __('about.testimonials_title'); ?></h2>
    <div class="row">
        <div class="col-lg-4 mb-4 scroll-animate fade-up delay-1">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="card-text mb-0">"Our guide Mario in Rome was fantastic! He showed us hidden gems we would never have found on our own. The best experience of our trip!"</p>
                    <p class="card-text"><small class="text-muted">— Sarah J., USA</small></p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4 scroll-animate fade-up delay-2">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="card-text mb-0">"The food tour in Bangkok was the highlight of our trip. Our guide knew all the local spots and the booking process was seamless."</p>
                    <p class="card-text"><small class="text-muted">— Thomas L., Germany</small></p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4 scroll-animate fade-up delay-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="card-text mb-0">"As a tour guide using this platform, I appreciate how it connects me with travelers who are truly interested in authentic experiences."</p>
                    <p class="card-text"><small class="text-muted">— Elena M., Tour Guide, Spain</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Join Us CTA Section -->
<div class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center scroll-animate scale-in">
                <h2 class="mb-4 text-white"><?php echo __('about.join_title'); ?></h2>
                <p class="lead mb-4 text-white"><?php echo __('about.join_desc'); ?></p>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="<?php echo url('account/register'); ?>" class="btn btn-light btn-lg px-4 me-sm-3 shadow"><?php echo __('about.sign_up_traveler'); ?></a>
                    <a href="<?php echo url('account/register/guide'); ?>" class="btn btn-outline-light btn-lg px-4 shadow"><?php echo __('nav.become_guide'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div> 