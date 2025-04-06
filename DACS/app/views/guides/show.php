<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <?php if (isset($guide)): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <img src="<?= !empty($guide->profile_image) ? $guide->profile_image : 'https://via.placeholder.com/200' ?>" class="profile-img img-fluid mb-3" alt="<?= htmlspecialchars($guide->getUser()->name) ?>">
                                <div class="rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= floor($guide->rating)): ?>
                                            <i class="fas fa-star"></i>
                                        <?php elseif ($i - 0.5 <= $guide->rating): ?>
                                            <i class="fas fa-star-half-alt"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span class="ml-1"><?= number_format($guide->rating, 1) ?></span>
                                </div>
                                <p class="text-muted">(<?= $guide->review_count ?> reviews)</p>
                            </div>
                            <div class="col-md-8">
                                <h1 class="h2 mb-3"><?= htmlspecialchars($guide->getUser()->name) ?></h1>
                                <p class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($guide->location ?? 'Location not specified') ?></p>
                                
                                <div class="mb-3">
                                    <p class="mb-1"><strong>Speciality:</strong> <?= htmlspecialchars($guide->speciality) ?></p>
                                    <p class="mb-1"><strong>Experience:</strong> <?= htmlspecialchars($guide->experience) ?> years</p>
                                    <p class="mb-1"><strong>Hourly Rate:</strong> $<?= number_format($guide->hourly_rate, 2) ?></p>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Languages:</strong>
                                    <?php if (is_array($guide->languages)): ?>
                                        <?php foreach ($guide->languages as $language): ?>
                                            <span class="badge badge-pill badge-light border"><?= htmlspecialchars($language) ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="d-flex flex-wrap">
                                    <a href="/bookings/create/<?= $guide->id ?>" class="btn btn-primary mr-2 mb-2"><i class="fas fa-calendar-check mr-1"></i> Book Tour</a>
                                    <button class="btn btn-outline-primary mr-2 mb-2" data-toggle="modal" data-target="#contactGuideModal"><i class="fas fa-envelope mr-1"></i> Contact</button>
                                    <button class="btn btn-outline-secondary mb-2" id="shareProfileBtn"><i class="fas fa-share-alt mr-1"></i> Share</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h2 class="h5 mb-0">About <?= htmlspecialchars($guide->getUser()->name) ?></h2>
                    </div>
                    <div class="card-body">
                        <p><?= nl2br(htmlspecialchars($guide->bio)) ?></p>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Availability</h2>
                    </div>
                    <div class="card-body">
                        <?php 
                        // This would be populated with actual availability data
                        $availability = $guide->getAvailability();
                        if (empty($availability)): 
                        ?>
                            <p class="text-muted">No availability information provided.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Day</th>
                                            <th>Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($availability as $slot): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($slot['day']) ?></td>
                                                <td><?= htmlspecialchars($slot['start_time']) ?> - <?= htmlspecialchars($slot['end_time']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card mb-4 mb-md-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">Reviews</h2>
                        <span class="badge badge-primary"><?= $guide->review_count ?> reviews</span>
                    </div>
                    <div class="card-body">
                        <?php 
                        // This would be populated with actual review data
                        $reviews = isset($reviews) ? $reviews : [];
                        if (empty($reviews)): 
                        ?>
                            <p class="text-muted">No reviews yet.</p>
                        <?php else: ?>
                            <?php foreach ($reviews as $review): ?>
                                <div class="review mb-4">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5><?= htmlspecialchars($review->getUser()->name) ?></h5>
                                            <div class="rating small">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <?php if ($i <= $review->rating): ?>
                                                        <i class="fas fa-star"></i>
                                                    <?php else: ?>
                                                        <i class="far fa-star"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <div class="text-muted small">
                                            <?= date('M d, Y', strtotime($review->created_at)) ?>
                                        </div>
                                    </div>
                                    <p class="mt-2"><?= nl2br(htmlspecialchars($review->comment)) ?></p>
                                    <hr>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer text-center">
                        <a href="/reviews/guide/<?= $guide->id ?>" class="btn btn-link">See all reviews</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Placeholder guide profile for development -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <img src="https://via.placeholder.com/200" class="profile-img img-fluid mb-3" alt="Jane Smith">
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <span class="ml-1">5.0</span>
                                </div>
                                <p class="text-muted">(87 reviews)</p>
                            </div>
                            <div class="col-md-8">
                                <h1 class="h2 mb-3">Jane Smith</h1>
                                <p class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> Paris, France</p>
                                
                                <div class="mb-3">
                                    <p class="mb-1"><strong>Speciality:</strong> Food Tours</p>
                                    <p class="mb-1"><strong>Experience:</strong> 8 years</p>
                                    <p class="mb-1"><strong>Hourly Rate:</strong> $45.00</p>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Languages:</strong>
                                    <span class="badge badge-pill badge-light border">English</span>
                                    <span class="badge badge-pill badge-light border">French</span>
                                </div>
                                
                                <div class="d-flex flex-wrap">
                                    <a href="#" class="btn btn-primary mr-2 mb-2"><i class="fas fa-calendar-check mr-1"></i> Book Tour</a>
                                    <button class="btn btn-outline-primary mr-2 mb-2"><i class="fas fa-envelope mr-1"></i> Contact</button>
                                    <button class="btn btn-outline-secondary mb-2"><i class="fas fa-share-alt mr-1"></i> Share</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h2 class="h5 mb-0">About Jane Smith</h2>
                    </div>
                    <div class="card-body">
                        <p>Jane is a passionate food enthusiast with over 8 years of experience leading culinary tours in Paris. Born and raised in France but educated internationally, she offers a unique perspective on Parisian cuisine and culture.</p>
                        <p>Her tours take visitors through hidden gems of Paris food scene, from traditional bakeries and cheese shops to trendy bistros that locals love. Jane specializes in helping visitors understand the history and culture behind French gastronomy.</p>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h2 class="h5 mb-0">Availability</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Hours</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Monday</td>
                                        <td>10:00 AM - 4:00 PM</td>
                                    </tr>
                                    <tr>
                                        <td>Wednesday</td>
                                        <td>10:00 AM - 4:00 PM</td>
                                    </tr>
                                    <tr>
                                        <td>Friday</td>
                                        <td>12:00 PM - 6:00 PM</td>
                                    </tr>
                                    <tr>
                                        <td>Saturday</td>
                                        <td>9:00 AM - 3:00 PM</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4 mb-md-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">Reviews</h2>
                        <span class="badge badge-primary">87 reviews</span>
                    </div>
                    <div class="card-body">
                        <div class="review mb-4">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>John Martin</h5>
                                    <div class="rating small">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    May 15, 2023
                                </div>
                            </div>
                            <p class="mt-2">Jane's food tour was the highlight of our Paris trip! She took us to places we would never have found on our own and gave us an amazing insight into French cuisine. Highly recommend!</p>
                            <hr>
                        </div>
                        
                        <div class="review mb-4">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Emily Johnson</h5>
                                    <div class="rating small">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    April 28, 2023
                                </div>
                            </div>
                            <p class="mt-2">What a wonderful experience! Jane is incredibly knowledgeable about French food and wine. She customized the tour to our preferences and dietary restrictions. The cheese shop she took us to was divine!</p>
                            <hr>
                        </div>
                        
                        <div class="review">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Robert Chen</h5>
                                    <div class="rating small">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    March 17, 2023
                                </div>
                            </div>
                            <p class="mt-2">Jane made our first visit to Paris so special. She's friendly, punctual, and extremely knowledgeable. The food was amazing and we learned so much about French culture through its cuisine. Worth every penny!</p>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="#" class="btn btn-link">See all reviews</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4 booking-card sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="h5 mb-0">Book a Tour</h3>
                </div>
                <div class="card-body">
                    <form action="/bookings/create/<?= $guide->id ?? 1 ?>" method="GET">
                        <input type="hidden" name="guide_id" value="<?= $guide->id ?? 1 ?>">
                        
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date" min="<?= date('Y-m-d') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tour_type">Tour Type</label>
                            <select class="form-control" id="tour_type" name="tour_type" required>
                                <option value="">Select Tour Type</option>
                                <option value="Walking Tour">Walking Tour</option>
                                <option value="Food Tour">Food Tour</option>
                                <option value="Historical Tour">Historical Tour</option>
                                <option value="Cultural Tour">Cultural Tour</option>
                                <option value="Custom Tour">Custom Tour</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="num_people">Number of People</label>
                            <select class="form-control" id="num_people" name="num_people" required>
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                                <option value="more">More than 10</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="duration">Duration (hours)</label>
                            <select class="form-control" id="duration" name="duration" required>
                                <option value="1">1 hour</option>
                                <option value="2">2 hours</option>
                                <option value="3">3 hours</option>
                                <option value="4">4 hours</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-block">Check Availability</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <p class="text-center mb-0"><small>No charge until booking is confirmed</small></p>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="h5 mb-0">Safety Tips</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> All guides verified</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> Secure payments</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> 24/7 support</li>
                        <li><i class="fas fa-check-circle text-success mr-2"></i> Free cancellation 48h before</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Guide Modal -->
<div class="modal fade" id="contactGuideModal" tabindex="-1" aria-labelledby="contactGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactGuideModalLabel">Contact <?= $guide->getUser()->name ?? 'Tour Guide' ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="contactGuideForm">
                    <div class="form-group">
                        <label for="contactSubject">Subject</label>
                        <input type="text" class="form-control" id="contactSubject" required>
                    </div>
                    <div class="form-group">
                        <label for="contactMessage">Message</label>
                        <textarea class="form-control" id="contactMessage" rows="5" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send Message</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Share profile functionality
    document.getElementById('shareProfileBtn')?.addEventListener('click', function() {
        if (navigator.share) {
            navigator.share({
                title: '<?= $guide->getUser()->name ?? 'Tour Guide' ?> - Tour Guide Profile',
                text: 'Check out this tour guide: <?= $guide->getUser()->name ?? 'Tour Guide' ?>',
                url: window.location.href,
            })
            .catch(error => console.log('Error sharing:', error));
        } else {
            alert('Sharing is not available in your browser');
        }
    });
</script> 