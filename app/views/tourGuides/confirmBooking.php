<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0 scroll-animate fade-up">
                <div class="card-header border-0 pb-0 text-center" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <h3 class="mb-2 text-white fw-bold py-3">
                        <i class="fas fa-clipboard-check me-2"></i>Confirm Tour Booking
                    </h3>
                    <p class="mb-0 text-white small">Please review your information before confirming</p>
                </div>
                <div class="card-body px-4 py-5">
                    <form action="<?php echo url('tourGuide/saveBooking'); ?>" method="post">
                        <input type="hidden" name="guide_id" value="<?php echo htmlspecialchars($guide_id); ?>">
                        <input type="hidden" name="booking_date" value="<?php echo htmlspecialchars($booking_date); ?>">
                        <input type="hidden" name="booking_type" value="<?php echo htmlspecialchars($booking_type); ?>">
                        <input type="hidden" name="start_time" value="<?php echo htmlspecialchars($start_time); ?>">
                        <input type="hidden" name="hours" value="<?php echo htmlspecialchars($hours); ?>">
                        <input type="hidden" name="number_of_people" value="<?php echo htmlspecialchars($number_of_people); ?>">
                        <input type="hidden" name="meeting_location" value="<?php echo htmlspecialchars($meeting_location); ?>">

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background-color: var(--warm-cream);">
                                    <div class="mb-2 text-muted small">
                                        <i class="far fa-calendar-alt me-1"></i>Date
                                    </div>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($booking_date); ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background-color: var(--warm-cream);">
                                    <div class="mb-2 text-muted small">
                                        <i class="fas fa-clock me-1"></i>Booking Type
                                    </div>
                                    <div class="fw-bold text-dark"><?php echo $booking_type === 'hourly' ? 'Hourly' : 'Full Day'; ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background-color: var(--warm-cream);">
                                    <div class="mb-2 text-muted small">
                                        <i class="far fa-clock me-1"></i>Start Time
                                    </div>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($start_time); ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background-color: var(--warm-cream);">
                                    <?php if($booking_type === 'hourly'): ?>
                                        <div class="mb-2 text-muted small">
                                            <i class="fas fa-hourglass-half me-1"></i>Number of Hours
                                        </div>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($hours); ?> hours</div>
                                    <?php else: ?>
                                        <div class="mb-2 text-muted small">
                                            <i class="fas fa-hourglass-half me-1"></i>Duration
                                        </div>
                                        <div class="fw-bold text-dark">8 hours (09:00 - 17:00)</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background-color: var(--warm-cream);">
                                    <div class="mb-2 text-muted small">
                                        <i class="fas fa-users me-1"></i>Number of People
                                    </div>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($number_of_people); ?> <?php echo $number_of_people == 1 ? 'person' : 'people'; ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background-color: var(--warm-cream);">
                                    <div class="mb-2 text-muted small">
                                        <i class="fas fa-map-marker-alt me-1"></i>Meeting Location
                                    </div>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($meeting_location); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="special_requests" class="form-label fw-bold mb-3">
                                <i class="fas fa-comment-dots me-2 text-primary"></i>Special Requests / Notes
                            </label>
                            <textarea class="form-control form-control-lg rounded-3 shadow-sm" id="special_requests" name="special_requests" rows="3" placeholder="Enter any notes or special requests..."><?php echo htmlspecialchars($special_requests ?? ''); ?></textarea>
                        </div>

                        <div class="mb-4 p-4 rounded-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-white fw-bold">
                                    <i class="fas fa-dollar-sign me-2"></i>Total Amount:
                                </h5>
                                <div class="text-end">
                                    <h4 class="mb-1 text-white fw-bold">$<?php echo number_format($total_amount, 2); ?> USD</h4>
                                    <small class="text-white" style="opacity: 0.8;">≈ <?php echo number_format($total_amount * 24500, 0, ',', '.'); ?> VND</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-lg rounded-pill px-4 shadow text-white" style="background: linear-gradient(135deg, #22543d 0%, #2f855a 100%);">
                                <i class="fas fa-check-circle me-2"></i>Confirm Booking
                            </button>
                            <a href="#" onclick="return processPayment(event)" class="btn btn-lg rounded-pill px-4 shadow text-white" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                <i class="fas fa-credit-card me-2"></i>Pay with VNPay
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-white border-0 py-3">
                    <small class="text-muted">
                        <i class="fas fa-lock me-1 text-primary"></i>Your information is absolutely secure
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function processPayment(event) {
    event.preventDefault();
    var amount = <?php echo $total_amount * 24500; ?>;
    var url = '<?php echo url('vnpay/createPayment'); ?>' + 
            '?amount=' + amount + 
            '&orderInfo=Tour Booking for Guide ID: <?php echo $guide_id; ?>' + 
            '&orderId=' + Date.now() + 
            '&guide_id=<?php echo $guide_id; ?>';
    window.location.href = url;
    return false;
}
</script>