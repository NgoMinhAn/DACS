<!-- Guide Profile Page -->
<div class="container mt-4 mb-5">
    <!-- Guide Profile Header -->
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo url(''); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo url('tourGuide/guides'); ?>">Tour Guides</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($guide->name); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Guide Profile Image and Core Info -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <img src="<?php echo url('assets/images/profiles/' . ($guide->profile_image ?? 'default.jpg')); ?>" 
                         alt="<?php echo htmlspecialchars($guide->name); ?>" 
                         class="rounded-circle img-thumbnail mb-3" 
                         style="width: 200px; height: 200px; object-fit: cover;">
                    
                    <h3 class="card-title"><?php echo htmlspecialchars($guide->name); ?></h3>
                    <p class="text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($guide->location ?? ''); ?>
                    </p>

                    <!-- Rating -->
                    <div class="mb-3">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?php if($i <= round($guide->avg_rating)): ?>
                                <i class="fas fa-star text-warning"></i>
                            <?php elseif($i - 0.5 <= $guide->avg_rating): ?>
                                <i class="fas fa-star-half-alt text-warning"></i>
                            <?php else: ?>
                                <i class="far fa-star text-warning"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <span class="ms-2">
                            <?php echo number_format($guide->avg_rating, 1); ?> 
                            (<?php echo $guide->total_reviews; ?> <?php echo $guide->total_reviews == 1 ? 'review' : 'reviews'; ?>)
                        </span>
                    </div>

                    <!-- Verification badge -->
                    <?php if($guide->verified): ?>
                        <div class="badge bg-success mb-3">
                            <i class="fas fa-check-circle me-1"></i> Verified Guide
                        </div>
                    <?php else: ?>
                        <div class="badge bg-warning mb-3">
                            <i class="fas fa-clock me-1"></i> Verification Pending
                        </div>
                    <?php endif; ?>

                    <!-- Experience -->
                    <p class="mb-2">
                        <i class="fas fa-briefcase me-2"></i>
                        <?php echo isset($guide->experience_years) ? (int)$guide->experience_years : 0; ?> years of experience
                    </p>

                    <!-- Quick info -->
                    <div class="list-group list-group-flush mb-4">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Hourly Rate</span>
                            <span class="badge bg-primary rounded-pill">$<?php echo number_format($guide->hourly_rate, 2); ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Daily Rate</span>
                            <span class="badge bg-primary rounded-pill">$<?php echo number_format($guide->daily_rate, 2); ?></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Availability</span>
                            <span class="badge <?php echo $guide->available ? 'bg-success' : 'bg-danger'; ?> rounded-pill">
                                <?php echo $guide->available ? 'Available' : 'Not Available'; ?>
                            </span>
                        </div>
                    </div>

                    <?php
                    $isUser = isLoggedIn() && $_SESSION['user_type'] === 'user';
                    ?>
                    <?php if ($isUser): ?>
                        <?php if($guide->available): ?>
                            <a href="#book-tour" class="btn btn-primary btn-lg w-100 mb-3">
                                <i class="fas fa-calendar-check me-2"></i> Book Now
                            </a>
                        <?php else: ?>
                            <button disabled class="btn btn-secondary btn-lg w-100 mb-3">
                                <i class="fas fa-calendar-times me-2"></i> Currently Unavailable
                            </button>
                        <?php endif; ?>
                        <!-- Contact Button with authentication check -->
                        <?php if (isset($requires_auth) && $requires_auth): ?>
                            <a href="<?php echo url('account/login'); ?>" class="btn btn-outline-primary w-100">
                                <i class="fas fa-sign-in-alt me-2"></i> Login to Contact Guide
                            </a>
                            <small class="text-muted mt-2 d-block">You need to be logged in to contact guides</small>
                        <?php else: ?>
                            <a href="<?php echo url('tourGuide/contact/' . $guide->id); ?>" class="btn btn-outline-primary w-100">
                                <i class="fas fa-envelope me-2"></i> Contact Guide
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Guide Details -->
        <div class="col-md-8">
            <!-- Bio Card -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0">About <?php echo htmlspecialchars($guide->name); ?></h4>
                </div>
                <div class="card-body">
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($guide->bio ?? '')); ?></p>
                </div>
            </div>
            
            <!-- Specialties and Languages Cards -->
            <div class="row mb-4">
                <!-- Specialties -->
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-star me-2 text-primary"></i>Specialties</h5>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($specialties)): ?>
                                <?php foreach($specialties as $specialty): ?>
                                    <span class="badge bg-primary me-2 mb-2 p-2"><?php echo htmlspecialchars($specialty->name); ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">No specialties listed</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Languages -->
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-language me-2 text-primary"></i>Languages</h5>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($languages)): ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach($languages as $language): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><?php echo htmlspecialchars($language->name); ?></span>
                                            <span class="badge bg-info"><?php echo ucfirst(htmlspecialchars($language->fluency_level)); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-muted">No languages listed</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reviews Section -->
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Reviews</h4>
                    
                    <!-- Add Review Button (show only if logged in and not viewing own profile) -->
                    <?php if(isLoggedIn() && $_SESSION['user_id'] != $guide->user_id): ?>
                    <a href="<?php echo url('tourGuide/review/' . $guide->guide_id); ?>" class="btn btn-sm btn-outline-primary">                        
                         <i class="fas fa-edit me-1"></i> Write a Review</a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if(!empty($reviews)): ?>
                        <!-- Rating Summary -->
                        <div class="row align-items-center mb-4">
                            <div class="col-md-3 text-center">
                                <h1 class="display-4 fw-bold mb-0"><?php echo number_format($guide->avg_rating, 1); ?></h1>
                                <div class="mb-2">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if($i <= round($guide->avg_rating)): ?>
                                            <i class="fas fa-star text-warning"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-warning"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <p class="text-muted">
                                    <?php echo $guide->total_reviews; ?> 
                                    <?php echo $guide->total_reviews == 1 ? 'review' : 'reviews'; ?>
                                </p>
                            </div>
                            <div class="col-md-9">
                                <?php foreach($ratings_distribution as $rating => $data): ?>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-3" style="width: 60px;">
                                            <?php echo $rating; ?> <?php echo $rating == 1 ? 'star' : 'stars'; ?>
                                        </div>
                                        <div class="progress flex-grow-1" style="height: 10px;">
                                            <div class="progress-bar bg-warning" role="progressbar" 
                                                 style="width: <?php echo $data['percentage']; ?>%" 
                                                 aria-valuenow="<?php echo $data['percentage']; ?>" 
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="ms-3" style="width: 40px;">
                                            <?php echo $data['count']; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Individual Reviews -->
                        <div id="reviewsList">
                            <?php 
                            $totalReviews = count($reviews);
                            $initialReviews = array_slice($reviews, 0, 3);
                            foreach($initialReviews as $review): 
                            ?>
                                <div class="mb-4 pb-4 border-bottom review-item">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>
                                            <h5 class="mb-0">
                                                <?php 
                                                if (isset($review->user_name)) {
                                                    echo htmlspecialchars($review->user_name);
                                                } elseif (isset($review->name)) {
                                                    echo htmlspecialchars($review->name);
                                                } else {
                                                    echo 'Anonymous User';
                                                }
                                                ?>
                                            </h5>
                                            <p class="text-muted small mb-0">
                                                <?php 
                                                if (isset($review->created_at) && !empty($review->created_at)) {
                                                    echo date('F j, Y', strtotime($review->created_at));
                                                } else {
                                                    echo 'Recently';
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <div>
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= $review->rating): ?>
                                                    <i class="fas fa-star text-warning"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star text-warning"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($review->review_text ?? '')); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Hidden Reviews -->
                        <div id="hiddenReviews" style="display: none;">
                            <?php 
                            $remainingReviews = array_slice($reviews, 3);
                            foreach($remainingReviews as $review): 
                            ?>
                                <div class="mb-4 pb-4 border-bottom review-item">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>
                                            <h5 class="mb-0">
                                                <?php 
                                                if (isset($review->user_name)) {
                                                    echo htmlspecialchars($review->user_name);
                                                } elseif (isset($review->name)) {
                                                    echo htmlspecialchars($review->name);
                                                } else {
                                                    echo 'Anonymous User';
                                                }
                                                ?>
                                            </h5>
                                            <p class="text-muted small mb-0">
                                                <?php 
                                                if (isset($review->created_at) && !empty($review->created_at)) {
                                                    echo date('F j, Y', strtotime($review->created_at));
                                                } else {
                                                    echo 'Recently';
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <div>
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= $review->rating): ?>
                                                    <i class="fas fa-star text-warning"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star text-warning"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($review->review_text ?? '')); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if($totalReviews > 3): ?>
                            <div class="text-center mt-4" id="viewAllContainer">
                                <button class="btn btn-outline-primary" id="viewAllBtn">
                                    View All Reviews (<?php echo $totalReviews; ?>)
                                </button>
                            </div>
                        <?php endif; ?>

                        <script>
                            document.getElementById('viewAllBtn')?.addEventListener('click', function() {
                                document.getElementById('hiddenReviews').style.display = 'block';
                                document.getElementById('viewAllContainer').style.display = 'none';
                            });
                        </script>
                    
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No reviews yet for this guide.
                            <?php if(isLoggedIn() && $_SESSION['user_id'] != $guide->user_id): ?>
                                Be the first to leave a review!
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Booking Section -->
            <?php if ($isUser): ?>
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="mb-0" id="book-tour">Book a Tour with <?php echo htmlspecialchars($guide->name); ?></h4>
                </div>
                <div class="card-body">
                    <?php if($guide->available): ?>
                        <p class="mb-4">Select a date and time to book a tour with <?php echo htmlspecialchars($guide->name); ?>.</p>
                        
                        <!-- Simple booking form -->
                        <form action="<?php echo url('tourGuide/confirmBooking'); ?>" method="post">
                            <input type="hidden" name="guide_id" value="<?php echo $guide->guide_id; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="booking_date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="booking_date" name="booking_date" 
                                           min="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="booking_type" class="form-label">Booking Type</label>
                                    <select class="form-select" id="booking_type" name="booking_type" required>
                                        <option value="hourly">Hourly - $<?php echo number_format($guide->hourly_rate, 2); ?> per hour</option>
                                        <option value="daily">Full Day - $<?php echo number_format($guide->daily_rate, 2); ?></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row hourly-options">
                                <div class="col-md-6 mb-3">
                                    <label for="start_time" class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="hours" class="form-label">Number of Hours</label>
                                    <select class="form-select" id="hours" name="hours">
                                        <?php for($i = 1; $i <= 8; $i++): ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?> hour<?php echo $i > 1 ? 's' : ''; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row daily-options" style="display: none;">
                                <div class="col-md-12 mb-3">
                                    <p class="text-muted">Full day tours typically run from 9:00 AM to 5:00 PM.</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="number_of_people" class="form-label">Number of People</label>
                                    <select class="form-select" id="number_of_people" name="number_of_people" required>
                                        <?php for($i = 1; $i <= 10; $i++): ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?> person<?php echo $i > 1 ? 's' : ''; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="meeting_location" class="form-label">Meeting Location</label>
                                    <input type="text" class="form-control" id="meeting_location" name="meeting_location" 
                                           placeholder="Where would you like to meet?" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="special_requests" class="form-label">Special Requests (Optional)</label>
                                <textarea class="form-control" id="special_requests" name="special_requests" rows="3" 
                                          placeholder="Any special requirements or requests?"></textarea>
                            </div>
                            
                            <!-- Estimated Price -->
                            <div class="alert alert-info mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><strong>Estimated Price:</strong></span>
                                    <span id="estimated_price" class="h5 mb-0">$<?php echo number_format($guide->hourly_rate, 2); ?></span>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <?php if(isLoggedIn()): ?>
                                    <button type="submit" class="btn btn-primary btn-lg">Request Booking</button>
                                <?php else: ?>
                                                                        <a href="<?php echo url('account/login?redirect=' . urlencode('tourGuide/profile/' . $guide->guide_id)); ?>" class="btn btn-primary btn-lg">                                        Log in to Book                                    </a>
                                <?php endif; ?>
                            </div>
                               <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const bookingTypeSelect = document.getElementById('booking_type');
                                        const startTimeInput = document.getElementById('start_time');
                                        const hoursSelect = document.getElementById('hours');

                                        function setFullDayDefaults() {
                                            if (bookingTypeSelect.value === 'daily') {
                                                startTimeInput.value = '09:00';
                                                hoursSelect.value = 8;
                                            }
                                        }

                                        // When switching to Full Day, the default value is automatically assigned
                                        bookingTypeSelect.addEventListener('change', setFullDayDefaults);

                                        //When loading the page, if it is Full Day, it will also be assigned
                                        setFullDayDefaults();
                                    });
                                    </script>
                        </form>
                        
                        <!-- JavaScript for price calculation -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const bookingTypeSelect = document.getElementById('booking_type');
                                const hourlyOptions = document.querySelector('.hourly-options');
                                const dailyOptions = document.querySelector('.daily-options');
                                const hoursSelect = document.getElementById('hours');
                                const numberOfPeopleSelect = document.getElementById('number_of_people');
                                const estimatedPriceEl = document.getElementById('estimated_price');
                                
                                const hourlyRate = <?php echo $guide->hourly_rate; ?>;
                                const dailyRate = <?php echo $guide->daily_rate; ?>;
                                
                                function updateEstimatedPrice() {
                                    const bookingType = bookingTypeSelect.value;
                                    const hours = parseInt(hoursSelect.value);
                                    
                                    let price = 0;
                                    if (bookingType === 'hourly') {
                                        price = hourlyRate * hours;
                                    } else {
                                        price = dailyRate;
                                    }
                                    
                                    estimatedPriceEl.textContent = `$${price.toFixed(2)}`;
                                }
                                
                                bookingTypeSelect.addEventListener('change', function() {
                                    if (this.value === 'hourly') {
                                        hourlyOptions.style.display = 'flex';
                                        dailyOptions.style.display = 'none';
                                    } else {
                                        hourlyOptions.style.display = 'none';
                                        dailyOptions.style.display = 'flex';
                                    }
                                    updateEstimatedPrice();
                                });
                                
                                hoursSelect.addEventListener('change', updateEstimatedPrice);
                                
                                // Initialize
                                updateEstimatedPrice();
                            });
                        </script>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-2"></i> 
                            <?php echo htmlspecialchars($guide->name); ?> is currently not available for bookings. 
                            Please check back later or contact them for more information.
                        </div>
                        
                        <!-- Contact option -->
                        <div class="text-center mt-3">
                            <a href="<?php echo url('message/guide/' . $guide->id); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-envelope me-2"></i> Send Message
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
