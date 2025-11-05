<!-- Contact Hero Section -->
<div class="py-4 mb-4 scroll-animate fade-up" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 scroll-animate fade-left">
                <h1 class="display-5 fw-bold text-white mb-2">
                    <i class="fas fa-envelope-open me-2"></i>Contact Us
                </h1>
                <p class="text-white mb-3">Have questions? We're here to help you find the perfect tour guide.</p>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill px-3 py-1" style="background: rgba(255,255,255,0.2); color: white; font-size: 0.9rem;">
                        <i class="fas fa-headset me-1"></i>24/7 Support
                    </span>
                    <span class="badge rounded-pill px-3 py-1" style="background: rgba(255,255,255,0.2); color: white; font-size: 0.9rem;">
                        <i class="fas fa-clock me-1"></i>Quick Response
                    </span>
                </div>
            </div>
            <div class="col-lg-6 scroll-animate fade-right text-center">
                <img src="<?php echo url('public/img/contact-hero.jpg'); ?>" alt="Customer Support Team" class="rounded-3 shadow" style="border: 3px solid rgba(255,255,255,0.1); width: 100%; height: 200px; object-fit: cover; object-position: center;">
            </div>
        </div>
    </div>
</div>

<!-- Contact Information and Form Section -->
<div class="container mb-5">
    <div class="row g-5">
        <!-- Contact Information Column -->
        <div class="col-lg-4 scroll-animate fade-left">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-header border-0 pb-0" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <h2 class="h4 mb-0 text-white fw-bold py-3">
                        <i class="fas fa-phone-alt me-2"></i>Get In Touch
                    </h2>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex mb-4 p-3 rounded" style="background-color: var(--warm-cream);">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle p-3 text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); width: 60px; height: 60px;">
                                <i class="fas fa-map-marker-alt fa-lg"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1 fw-bold">Our Location</h5>
                            <p class="text-muted mb-0"><?php echo getConfig('contact.address'); ?></p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4 p-3 rounded" style="background-color: var(--warm-cream);">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle p-3 text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); width: 60px; height: 60px;">
                                <i class="fas fa-phone fa-lg"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1 fw-bold">Phone Number</h5>
                            <p class="text-muted mb-0"><?php echo getConfig('contact.phone'); ?></p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4 p-3 rounded" style="background-color: var(--warm-cream);">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle p-3 text-white d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); width: 60px; height: 60px;">
                                <i class="fas fa-envelope fa-lg"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1 fw-bold">Email Address</h5>
                            <p class="text-muted mb-0"><?php echo getConfig('contact.email'); ?></p>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5 class="mb-3 fw-bold">
                        <i class="fas fa-share-alt me-2 text-primary"></i>Follow Us
                    </h5>
                    <div class="social-links">
                        <a href="<?php echo getConfig('social_media.facebook'); ?>" class="btn btn-outline-primary rounded-pill me-2 mb-2" target="_blank" rel="noopener">
                            <i class="fab fa-facebook-f me-2"></i>Facebook
                        </a>
                        <a href="<?php echo getConfig('social_media.twitter'); ?>" class="btn btn-outline-primary rounded-pill me-2 mb-2" target="_blank" rel="noopener">
                            <i class="fab fa-twitter me-2"></i>Twitter
                        </a>
                        <a href="<?php echo getConfig('social_media.instagram'); ?>" class="btn btn-outline-primary rounded-pill me-2 mb-2" target="_blank" rel="noopener">
                            <i class="fab fa-instagram me-2"></i>Instagram
                        </a>
                        <a href="<?php echo getConfig('social_media.linkedin'); ?>" class="btn btn-outline-primary rounded-pill me-2 mb-2" target="_blank" rel="noopener">
                            <i class="fab fa-linkedin-in me-2"></i>LinkedIn
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Form Column -->
        <div class="col-lg-8 scroll-animate fade-right">
            <div class="card border-0 shadow-lg">
                <div class="card-header border-0 pb-0" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <h2 class="h4 mb-0 text-white fw-bold py-3">
                        <i class="fas fa-paper-plane me-2"></i>Send Us a Message
                    </h2>
                </div>
                <div class="card-body p-4 p-md-5">
                    <?php flash('contact_message'); ?>
                    
                    <form action="<?php echo url('page/submit_contact'); ?>" method="post" id="contactForm">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold">
                                    <i class="fas fa-user me-2 text-primary"></i>Your Name
                                </label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Your full name" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-2 text-primary"></i>Email Address
                                </label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Your email address" required>
                            </div>
                            
                            <div class="col-12">
                                <label for="subject" class="form-label fw-bold">
                                    <i class="fas fa-tag me-2 text-primary"></i>Subject
                                </label>
                                <input type="text" class="form-control form-control-lg" id="subject" name="subject" placeholder="What is this regarding?" required>
                            </div>
                            
                            <div class="col-12">
                                <label for="message" class="form-label fw-bold">
                                    <i class="fas fa-comment-dots me-2 text-primary"></i>Message
                                </label>
                                <textarea class="form-control form-control-lg" id="message" name="message" rows="6" placeholder="How can we help you?" required></textarea>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check p-3 rounded" style="background-color: var(--warm-cream);">
                                    <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
                                    <label class="form-check-label" for="privacy">
                                        I agree to the <a href="<?php echo url('privacy'); ?>" class="text-primary fw-bold">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-lg rounded-pill px-5 shadow text-white" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="py-5 mb-5" style="background-color: var(--warm-cream);">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5 scroll-animate fade-up">
                <h2 class="fw-bold display-5 mb-3">
                    <i class="fas fa-question-circle me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
                    Frequently Asked Questions
                </h2>
                <p class="text-muted lead">Find answers to common questions about our platform</p>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10 scroll-animate fade-up delay-1">
                <div class="accordion shadow-sm" id="faqAccordion">
                    <div class="accordion-item border-0 mb-3 rounded">
                        <h3 class="accordion-header" id="faqHeading1">
                            <button class="accordion-button rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;">
                                <i class="fas fa-calendar-check me-2"></i>How do I book a tour with a guide?
                            </button>
                        </h3>
                        <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                You can browse our available guides, view their profiles, and request a booking directly through our platform. After selecting a guide, choose a date and time, specify your group size, and send your request. The guide will confirm your booking within 24 hours.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 rounded">
                        <h3 class="accordion-header" id="faqHeading2">
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;">
                                <i class="fas fa-times-circle me-2"></i>What is your cancellation policy?
                            </button>
                        </h3>
                        <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We offer a flexible cancellation policy. Cancellations made more than 48 hours before the scheduled tour receive a full refund. Cancellations within 48 hours may be subject to a fee depending on the guide's policy. Emergency situations are handled on a case-by-case basis.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 rounded">
                        <h3 class="accordion-header" id="faqHeading3">
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;">
                                <i class="fas fa-route me-2"></i>Can I request a custom tour?
                            </button>
                        </h3>
                        <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Absolutely! Many of our guides offer customized experiences. When booking, you can include your preferences and special requests in the notes. Alternatively, you can contact the guide directly to discuss a personalized itinerary before booking.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 rounded">
                        <h3 class="accordion-header" id="faqHeading4">
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;">
                                <i class="fas fa-user-tie me-2"></i>How do I become a guide on your platform?
                            </button>
                        </h3>
                        <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                To become a guide, click on the "Become a Guide" button in the navigation bar and complete our application process. You'll need to provide information about your expertise, languages spoken, and availability. We'll review your application and may schedule an interview before approving your profile.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item border-0 mb-3 rounded">
                        <h3 class="accordion-header" id="faqHeading5">
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;">
                                <i class="fas fa-check-circle me-2"></i>Are the guides on your platform verified?
                            </button>
                        </h3>
                        <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, all guides on our platform go through a thorough verification process. We verify their identity, qualifications, and expertise. We also collect and display authentic reviews from past travelers to help you make an informed choice.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Section -->
<div class="container mb-5">
    <div class="card border-0 shadow-lg scroll-animate fade-up">
        <div class="card-header border-0 pb-0" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
            <h3 class="mb-0 text-white fw-bold py-3">
                <i class="fas fa-map-marked-alt me-2"></i>Find Us On Map
            </h3>
        </div>
        <div class="card-body p-0">
            <div class="ratio ratio-21x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.657600383133!2d105.78236757374515!3d21.046398484339835!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab323f3a20f1%3A0x4898724834e6958!2sHanoi%20University%20of%20Science%20and%20Technology!5e0!3m2!1sen!2s!4v1715167113971!5m2!1sen!2s" 
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" style="border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Call To Action -->
<div class="py-5 scroll-animate scale-in" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-7 mb-4 mb-lg-0 scroll-animate fade-left">
                <h2 class="h3 mb-3 text-white fw-bold">
                    <i class="fas fa-rocket me-2"></i>Ready to start your adventure?
                </h2>
                <p class="lead mb-0 text-white">Find a local guide to show you the authentic experience of your destination.</p>
            </div>
            <div class="col-lg-4 text-lg-end scroll-animate fade-right">
                <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-light btn-lg rounded-pill px-4 shadow">
                    <i class="fas fa-th-large me-2"></i>Browse Tour Guides
                </a>
            </div>
        </div>
    </div>
</div> 