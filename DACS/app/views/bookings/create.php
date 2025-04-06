<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Book Your Tour</h1>
                </div>
                <div class="card-body">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i> You need to <a href="/users/login" class="alert-link">log in</a> or <a href="/users/register" class="alert-link">register</a> to book a tour.
                        </div>
                    <?php else: ?>
                        <?php if (isset($guide)): ?>
                            <div class="mb-4">
                                <div class="row">
                                    <div class="col-md-2 text-center">
                                        <img src="<?= !empty($guide->profile_image) ? $guide->profile_image : 'https://via.placeholder.com/80' ?>" class="img-fluid rounded-circle mb-2" alt="Guide Photo" style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="col-md-10">
                                        <h2 class="h5 mb-1">Booking with <?= htmlspecialchars($guide->getUser()->name) ?></h2>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($guide->location) ?>
                                            <span class="mx-2">|</span>
                                            <i class="fas fa-star text-warning mr-1"></i> <?= number_format($guide->rating, 1) ?> (<?= $guide->review_count ?> reviews)
                                        </p>
                                        <p class="mb-1 mt-2"><strong>Rate:</strong> $<?= number_format($guide->hourly_rate, 2) ?> / hour</p>
                                    </div>
                                </div>
                            </div>

                            <?php 
                            // This would come from the form in the tour guide's detail page
                            $date = $_GET['date'] ?? date('Y-m-d');
                            $tour_type = $_GET['tour_type'] ?? '';
                            $num_people = $_GET['num_people'] ?? 1;
                            $duration = $_GET['duration'] ?? 2;
                            ?>

                            <form action="/bookings/store" method="POST">
                                <input type="hidden" name="guide_id" value="<?= $guide->id ?>">
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="date">Tour Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($date) ?>" min="<?= date('Y-m-d') ?>" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="time">Start Time <span class="text-danger">*</span></label>
                                        <input type="time" class="form-control" id="time" name="time" required>
                                        <small class="form-text text-muted">Please check guide's availability for available times</small>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="tour_type">Tour Type <span class="text-danger">*</span></label>
                                        <select class="form-control" id="tour_type" name="tour_type" required>
                                            <option value="" disabled <?= empty($tour_type) ? 'selected' : '' ?>>Select Tour Type</option>
                                            <option value="Walking Tour" <?= $tour_type === 'Walking Tour' ? 'selected' : '' ?>>Walking Tour</option>
                                            <option value="Food Tour" <?= $tour_type === 'Food Tour' ? 'selected' : '' ?>>Food Tour</option>
                                            <option value="Historical Tour" <?= $tour_type === 'Historical Tour' ? 'selected' : '' ?>>Historical Tour</option>
                                            <option value="Cultural Tour" <?= $tour_type === 'Cultural Tour' ? 'selected' : '' ?>>Cultural Tour</option>
                                            <option value="Custom Tour" <?= $tour_type === 'Custom Tour' ? 'selected' : '' ?>>Custom Tour</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="duration">Duration (hours) <span class="text-danger">*</span></label>
                                        <select class="form-control" id="duration" name="duration" required>
                                            <option value="1" <?= $duration == 1 ? 'selected' : '' ?>>1 hour ($<?= number_format($guide->hourly_rate * 1, 2) ?>)</option>
                                            <option value="2" <?= $duration == 2 ? 'selected' : '' ?>>2 hours ($<?= number_format($guide->hourly_rate * 2, 2) ?>)</option>
                                            <option value="3" <?= $duration == 3 ? 'selected' : '' ?>>3 hours ($<?= number_format($guide->hourly_rate * 3, 2) ?>)</option>
                                            <option value="4" <?= $duration == 4 ? 'selected' : '' ?>>4 hours ($<?= number_format($guide->hourly_rate * 4, 2) ?>)</option>
                                            <option value="5" <?= $duration == 5 ? 'selected' : '' ?>>5 hours ($<?= number_format($guide->hourly_rate * 5, 2) ?>)</option>
                                            <option value="6" <?= $duration == 6 ? 'selected' : '' ?>>6 hours ($<?= number_format($guide->hourly_rate * 6, 2) ?>)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="num_people">Number of People <span class="text-danger">*</span></label>
                                        <select class="form-control" id="num_people" name="num_people" required>
                                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                                <option value="<?= $i ?>" <?= $num_people == $i ? 'selected' : '' ?>><?= $i ?> <?= $i === 1 ? 'person' : 'people' ?></option>
                                            <?php endfor; ?>
                                            <option value="more" <?= $num_people === 'more' ? 'selected' : '' ?>>More than 10 (contact guide)</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="meeting_point">Meeting Point <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="meeting_point" name="meeting_point" placeholder="e.g. Hotel lobby, Tourist center" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="special_requests">Special Requests or Requirements</label>
                                    <textarea class="form-control" id="special_requests" name="special_requests" rows="3" placeholder="Any dietary requirements, accessibility needs, or specific interests?"></textarea>
                                </div>

                                <hr class="my-4">

                                <h3 class="h5 mb-3">Price Summary</h3>
                                <div class="card bg-light mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Guide Rate:</span>
                                            <span>$<?= number_format($guide->hourly_rate, 2) ?> x <span id="duration-display"><?= $duration ?></span> hours</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Number of People:</span>
                                            <span id="people-display"><?= $num_people === 'more' ? 'More than 10' : $num_people ?></span>
                                        </div>
                                        <?php if ($num_people > 5): ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Large Group Fee:</span>
                                            <span>$<?= number_format(($num_people - 5) * 10, 2) ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Service Fee:</span>
                                            <span>$<?= number_format($guide->hourly_rate * $duration * 0.1, 2) ?></span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between font-weight-bold">
                                            <span>Total:</span>
                                            <span id="total-price">
                                                $<?php
                                                $base_price = $guide->hourly_rate * $duration;
                                                $large_group_fee = ($num_people > 5 && $num_people !== 'more') ? ($num_people - 5) * 10 : 0;
                                                $service_fee = $base_price * 0.1;
                                                $total = $base_price + $large_group_fee + $service_fee;
                                                echo number_format($total, 2);
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">I agree to the <a href="/terms" target="_blank">Terms of Service</a> and <a href="/privacy" target="_blank">Privacy Policy</a></label>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">Confirm Booking</button>
                                    <p class="text-muted mt-2"><small>You will not be charged until the guide confirms</small></p>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle mr-2"></i> Guide information not found. Please <a href="/guides" class="alert-link">select a guide</a> to book a tour.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Guide Availability Modal -->
<div class="modal fade" id="availabilityModal" tabindex="-1" aria-labelledby="availabilityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="availabilityModalLabel">Guide Availability</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($guide) && !empty($guide->getAvailability())): ?>
                                <?php foreach ($guide->getAvailability() as $slot): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($slot['day']) ?></td>
                                        <td><?= htmlspecialchars($slot['start_time']) ?> - <?= htmlspecialchars($slot['end_time']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
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
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Update price calculation in real-time when inputs change
    document.addEventListener('DOMContentLoaded', function() {
        const durationSelect = document.getElementById('duration');
        const numPeopleSelect = document.getElementById('num_people');
        const totalPriceDisplay = document.getElementById('total-price');
        const durationDisplay = document.getElementById('duration-display');
        const peopleDisplay = document.getElementById('people-display');
        
        // Show availability modal link
        const timeInput = document.getElementById('time');
        if (timeInput) {
            const smallElement = timeInput.nextElementSibling;
            smallElement.innerHTML += ' <a href="#" data-toggle="modal" data-target="#availabilityModal">View availability</a>';
        }
        
        // Function to update price calculation
        function updatePrice() {
            if (!durationSelect || !numPeopleSelect || !totalPriceDisplay) return;
            
            const hourlyRate = <?= isset($guide) ? $guide->hourly_rate : 45 ?>;
            const duration = parseInt(durationSelect.value);
            const numPeople = numPeopleSelect.value === 'more' ? 11 : parseInt(numPeopleSelect.value);
            
            const basePrice = hourlyRate * duration;
            const largeGroupFee = numPeople > 5 ? (numPeople - 5) * 10 : 0;
            const serviceFee = basePrice * 0.1;
            const total = basePrice + largeGroupFee + serviceFee;
            
            totalPriceDisplay.textContent = '$' + total.toFixed(2);
            
            if (durationDisplay) {
                durationDisplay.textContent = duration;
            }
            
            if (peopleDisplay) {
                peopleDisplay.textContent = numPeopleSelect.value === 'more' ? 'More than 10' : numPeople;
            }
        }
        
        // Add event listeners
        if (durationSelect) {
            durationSelect.addEventListener('change', updatePrice);
        }
        
        if (numPeopleSelect) {
            numPeopleSelect.addEventListener('change', updatePrice);
        }
    });
</script> 