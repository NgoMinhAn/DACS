<!-- Contact Hero Section -->
<div class="bg-light py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">Contact Us</h1>
                <p class="lead text-muted">Have questions? We're here to help you find the perfect tour guide.</p>
            </div>
            <div class="col-lg-6">
                <img src="<?php echo url('public/images/contact-hero.jpg'); ?>" alt="Customer Support Team" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</div>

<!-- Contact Information and Form Section -->
<div class="container mb-5">
    <div class="row g-5">
        <!-- Contact Information Column -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h4 mb-4">Get In Touch</h2>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-primary rounded-circle p-3 text-white">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1">Our Location</h5>
                            <p class="text-muted mb-0"><?php echo getConfig('contact.address'); ?></p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-primary rounded-circle p-3 text-white">
                                <i class="fas fa-phone"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1">Phone Number</h5>
                            <p class="text-muted mb-0"><?php echo getConfig('contact.phone'); ?></p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-primary rounded-circle p-3 text-white">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1">Email Address</h5>
                            <p class="text-muted mb-0"><?php echo getConfig('contact.email'); ?></p>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5 class="mb-3">Follow Us</h5>
                    <div class="social-links">
                        <a href="<?php echo getConfig('social_media.facebook'); ?>" class="btn btn-outline-primary me-2" target="_blank" rel="noopener">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="<?php echo getConfig('social_media.twitter'); ?>" class="btn btn-outline-primary me-2" target="_blank" rel="noopener">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="<?php echo getConfig('social_media.instagram'); ?>" class="btn btn-outline-primary me-2" target="_blank" rel="noopener">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="<?php echo getConfig('social_media.linkedin'); ?>" class="btn btn-outline-primary me-2" target="_blank" rel="noopener">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Form Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h2 class="h4 mb-4">Send Us a Message</h2>
                    
                    <?php flash('contact_message'); ?>
                    
                    <form action="<?php echo url('page/submit_contact'); ?>" method="post" id="contactForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Your full name" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Your email address" required>
                            </div>
                            
                            <div class="col-12">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="What is this regarding?" required>
                            </div>
                            
                            <div class="col-12">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="6" placeholder="How can we help you?" required></textarea>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
                                    <label class="form-check-label" for="privacy">
                                        I agree to the <a href="<?php echo url('privacy'); ?>">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="bg-light py-5 mb-5">
    <div class="container">
        <h2 class="text-center mb-5">Frequently Asked Questions</h2>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="faqHeading1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                                How do I book a tour with a guide?
                            </button>
                        </h3>
                        <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                You can browse our available guides, view their profiles, and request a booking directly through our platform. After selecting a guide, choose a date and time, specify your group size, and send your request. The guide will confirm your booking within 24 hours.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="faqHeading2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                What is your cancellation policy?
                            </button>
                        </h3>
                        <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                We offer a flexible cancellation policy. Cancellations made more than 48 hours before the scheduled tour receive a full refund. Cancellations within 48 hours may be subject to a fee depending on the guide's policy. Emergency situations are handled on a case-by-case basis.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="faqHeading3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                Can I request a custom tour?
                            </button>
                        </h3>
                        <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Absolutely! Many of our guides offer customized experiences. When booking, you can include your preferences and special requests in the notes. Alternatively, you can contact the guide directly to discuss a personalized itinerary before booking.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="faqHeading4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                                How do I become a guide on your platform?
                            </button>
                        </h3>
                        <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                To become a guide, click on the "Become a Guide" button in the navigation bar and complete our application process. You'll need to provide information about your expertise, languages spoken, and availability. We'll review your application and may schedule an interview before approving your profile.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="faqHeading5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                                Are the guides on your platform verified?
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
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="ratio ratio-21x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.657600383133!2d105.78236757374515!3d21.046398484339835!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab323f3a20f1%3A0x4898724834e6958!2sHanoi%20University%20of%20Science%20and%20Technology!5e0!3m2!1sen!2s!4v1715167113971!5m2!1sen!2s" 
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Call To Action -->
<div class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <h2 class="h3 mb-3">Ready to start your adventure?</h2>
                <p class="lead mb-0">Find a local guide to show you the authentic experience of your destination.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-light btn-lg">Browse Tour Guides</a>
            </div>
        </div>
    </div>
</div> 