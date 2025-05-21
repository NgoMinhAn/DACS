<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white text-center py-4" style="background: linear-gradient(90deg,#007bff,#00c6ff);">
                    <h3 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Confirm Tour Booking</h3>
                    <p class="mb-0 small">Please review your information before confirming</p>
                </div>
                <div class="card-body px-4 py-4">
                    <form action="<?php echo url('tourGuide/saveBooking'); ?>" method="post">
                        <input type="hidden" name="guide_id" value="<?php echo htmlspecialchars($guide_id); ?>">
                        <input type="hidden" name="booking_date" value="<?php echo htmlspecialchars($booking_date); ?>">
                        <input type="hidden" name="booking_type" value="<?php echo htmlspecialchars($booking_type); ?>">
                        <input type="hidden" name="start_time" value="<?php echo htmlspecialchars($start_time); ?>">
                        <input type="hidden" name="hours" value="<?php echo htmlspecialchars($hours); ?>">
                        <input type="hidden" name="number_of_people" value="<?php echo htmlspecialchars($number_of_people); ?>">
                        <input type="hidden" name="meeting_location" value="<?php echo htmlspecialchars($meeting_location); ?>">

                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="mb-2 text-muted small">Date</div>
                                <div class="fw-bold"><i class="far fa-calendar-alt me-2 text-primary"></i><?php echo htmlspecialchars($booking_date); ?></div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2 text-muted small">Booking Type</div>
                                <div class="fw-bold"><i class="fas fa-clock me-2 text-primary"></i><?php echo $booking_type === 'hourly' ? 'Hourly' : 'Full Day'; ?></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="mb-2 text-muted small">Start Time</div>
                                <div class="fw-bold"><i class="far fa-clock me-2 text-primary"></i><?php echo htmlspecialchars($start_time); ?></div>
                            </div>
                            <div class="col-6">
                                <?php if($booking_type === 'hourly'): ?>
                                    <div class="mb-2 text-muted small">Number of Hours</div>
                                    <div class="fw-bold"><i class="fas fa-hourglass-half me-2 text-primary"></i><?php echo htmlspecialchars($hours); ?></div>
                                <?php else: ?>
                                    <div class="mb-2 text-muted small">Duration</div>
                                    <div class="fw-bold"><i class="fas fa-hourglass-half me-2 text-primary"></i>8 hours (09:00 - 17:00)</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="mb-2 text-muted small">Number of People</div>
                                <div class="fw-bold"><i class="fas fa-users me-2 text-primary"></i><?php echo htmlspecialchars($number_of_people); ?></div>
                            </div>
                            <div class="col-6">
                                <div class="mb-2 text-muted small">Meeting Location</div>
                                <div class="fw-bold"><i class="fas fa-map-marker-alt me-2 text-primary"></i><?php echo htmlspecialchars($meeting_location); ?></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="special_requests" class="form-label text-muted small">Special Requests / Notes</label>
                            <textarea class="form-control rounded-3 shadow-sm" id="special_requests" name="special_requests" rows="3" placeholder="Enter any notes or special requests..."><?php echo htmlspecialchars($special_requests); ?></textarea>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Total Amount:</h5>
                                <div class="text-end">
                                    <h4 class="mb-0 text-primary">$<?php echo number_format($total_amount, 2); ?> USD</h4>
                                    <small class="text-muted">≈ <?php echo number_format($total_amount * 24500, 0, ',', '.'); ?> VND</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg shadow mb-2">
                                <i class="fas fa-check-circle me-2"></i>Confirm Booking
                            </button>
                            <a href="#" onclick="return processPayment(event)" class="btn btn-primary btn-lg shadow">
                                <i class="fas fa-credit-card me-2"></i>Pay with VNPay
                            </a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light py-3">
                    <small class="text-muted"><i class="fas fa-lock me-1"></i>Your information is absolutely secure</small>
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