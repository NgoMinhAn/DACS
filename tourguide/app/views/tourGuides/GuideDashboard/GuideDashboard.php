<!-- Guide Dashboard -->
<div class="container mt-4 mb-5">
    <div class="row">
        <!-- Guide Info Section -->
        <div class="col-md-4">
            <div class="card mb-4 border-0 shadow-lg scroll-animate fade-up">
                <div class="card-header border-0 pb-0" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-user-circle me-2"></i>Guide Profile
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    <div class="position-relative d-inline-block mb-3">
                        <img src="<?php echo url('assets/images/profiles/' . ($guide->profile_image ?? 'default.jpg')); ?>"
                            alt="<?php echo htmlspecialchars($guide->name); ?>" class="rounded-circle"
                            style="width: 150px; height: 150px; object-fit: cover; border: 5px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                        <?php if($guide->verified): ?>
                            <div class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2" style="border: 3px solid white;">
                                <i class="fas fa-check text-white"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h4 class="fw-bold mb-2"><?php echo htmlspecialchars($guide->name); ?></h4>
                    <p class="text-muted mb-3">
                        <i class="fas fa-map-marker-alt me-2 text-primary"></i><?php echo htmlspecialchars($guide->location); ?>
                    </p>

                    <div class="mb-3 p-3 rounded" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                        <div class="mb-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= round($guide->avg_rating)): ?>
                                    <i class="fas fa-star text-white"></i>
                                <?php else: ?>
                                    <i class="far fa-star text-white"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <div class="text-white">
                            <strong class="h5 mb-0"><?php echo number_format($guide->avg_rating, 1); ?></strong>
                            <small class="d-block"><?php echo $guide->total_reviews; ?> <?php echo $guide->total_reviews == 1 ? 'review' : 'reviews'; ?></small>
                        </div>
                    </div>

                    <div class="d-grid">
                        <a href="<?php echo url('guide/edit-profile'); ?>" class="btn btn-lg shadow text-white" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background-color: var(--warm-cream);">
                            <span class="fw-medium"><i class="fas fa-toggle-<?php echo $guide->available ? 'on' : 'off'; ?> me-2 text-primary"></i>Status:</span>
                            <span class="badge <?php echo $guide->available ? 'bg-success' : 'bg-danger'; ?> rounded-pill px-3 py-2">
                                <?php echo $guide->available ? 'Available' : 'Not Available'; ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded mt-2" style="background-color: var(--warm-cream);">
                            <span class="fw-medium"><i class="fas fa-clock me-2 text-primary"></i>Hourly Rate:</span>
                            <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;">$<?php echo number_format($guide->hourly_rate, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded mt-2" style="background-color: var(--warm-cream);">
                            <span class="fw-medium"><i class="fas fa-calendar-day me-2 text-primary"></i>Daily Rate:</span>
                            <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;">$<?php echo number_format($guide->daily_rate, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded mt-2" style="background-color: var(--warm-cream);">
                            <span class="fw-medium"><i class="fas fa-briefcase me-2 text-primary"></i>Experience:</span>
                            <span class="fw-bold"><?php echo $guide->experience_years; ?> years</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center p-3 rounded mt-2" style="background-color: var(--warm-cream);">
                            <span class="fw-medium"><i class="fas fa-check-circle me-2 text-primary"></i>Verification:</span>
                            <span class="badge <?php echo $guide->verified ? 'bg-success' : 'bg-warning'; ?> rounded-pill px-3 py-2">
                                <?php echo $guide->verified ? 'Verified' : 'Pending'; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 pt-0">
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i>Member since: <?php echo date('M Y', strtotime($guide->created_at)); ?>
                    </small>
                </div>
            </div>

            <!-- Specialties Card -->
            <div class="card mb-4 border-0 shadow-sm scroll-animate fade-up delay-1">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-star me-2"></i>Your Specialties
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($specialties)): ?>
                        <?php foreach ($specialties as $specialty): ?>
                            <span class="badge rounded-pill px-3 py-2 mb-2 me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;"><?php echo htmlspecialchars($specialty->name); ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No specialties added yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Languages Card -->
            <div class="card mb-4 border-0 shadow-sm scroll-animate fade-up delay-2">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-language me-2"></i>Your Languages
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($languages)): ?>
                        <?php foreach ($languages as $language): ?>
                            <div class="mb-2 d-flex justify-content-between align-items-center p-2 rounded" style="background-color: var(--warm-cream);">
                                <span class="fw-medium"><?php echo htmlspecialchars($language->name); ?></span>
                                <span class="badge rounded-pill px-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;"><?php echo htmlspecialchars($language->fluency_level); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No languages added yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Dashboard Content Section -->
        <div class="col-md-8">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100 border-0 shadow-sm scroll-animate fade-up">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-calendar-check fa-2x" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            </div>
                            <h5 class="card-title text-muted mb-2">Total Bookings</h5>
                            <p class="display-5 fw-bold mb-0"><?php echo $stats->total_bookings ?? 0; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100 border-0 shadow-sm scroll-animate fade-up delay-1">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-star fa-2x" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            </div>
                            <h5 class="card-title text-muted mb-2">Total Reviews</h5>
                            <p class="display-5 fw-bold mb-0"><?php echo $guide->total_reviews ?? 0; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100 border-0 shadow-sm scroll-animate fade-up delay-2">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-clock fa-2x" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            </div>
                            <h5 class="card-title text-muted mb-2">Pending Bookings</h5>
                            <p class="display-5 fw-bold mb-0"><?php echo $stats->pending_bookings ?? 0; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100 border-0 shadow-sm scroll-animate fade-up delay-3">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <i class="fas fa-dollar-sign fa-2x" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                            </div>
                            <h5 class="card-title text-muted mb-2">Revenue (This Month)</h5>
                            <p class="display-5 fw-bold mb-0">$<?php echo number_format($stats->monthly_revenue ?? 0, 0); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings Card -->
            <div class="card mb-4 border-0 shadow-sm scroll-animate fade-up">
                <div class="card-header border-0 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>Recent Bookings
                    </h5>
                    <a href="<?php echo url('guide/bookings'); ?>" class="btn btn-sm btn-light shadow">View All</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_bookings)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_bookings as $booking): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-2">
                                                        <div class="fw-bold"><?php echo htmlspecialchars($booking->client_name); ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($booking->booking_date)); ?></td>
                                            <td><?php echo date('h:i A', strtotime($booking->start_time)) . ' - ' . date('h:i A', strtotime($booking->end_time)); ?></td>
                                            <td>
                                                <span class="badge <?php
                                                    switch ($booking->status) {
                                                        case 'confirmed':
                                                            echo 'bg-success';
                                                            break;
                                                        case 'pending':
                                                            echo 'bg-warning';
                                                            break;
                                                        case 'cancelled':
                                                            echo 'bg-danger';
                                                            break;
                                                        case 'completed':
                                                            echo 'bg-info';
                                                            break;
                                                        default:
                                                            echo 'bg-secondary';
                                                    }
                                                    ?>">
                                                    <?php echo ucfirst($booking->status); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge <?php echo $booking->payment_status === 'paid' ? 'bg-success' : 'bg-warning'; ?>">
                                                    <?php echo ucfirst($booking->payment_status); ?>
                                                </span>
                                            </td>
                                            <td>$<?php echo number_format($booking->total_price, 2); ?></td>
                                            <td>
                                                <a href="<?php echo url('guide/booking/' . $booking->id); ?>"
                                                    class="btn btn-sm btn-outline-primary rounded-pill">Details</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i> You don't have any bookings yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Reviews Card -->
            <div class="card mb-4 border-0 shadow-sm scroll-animate fade-up">
                <div class="card-header border-0 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-star me-2"></i>Recent Reviews
                    </h5>
                    <a href="<?php echo url('guide/reviews'); ?>" class="btn btn-sm btn-light shadow">View All</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($recent_reviews)): ?>
                        <?php foreach ($recent_reviews as $review): ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <span class="fw-bold"><?php echo htmlspecialchars($review->user_name); ?></span>
                                        <span
                                            class="text-muted ms-2"><?php echo date('M d, Y', strtotime($review->created_at)); ?></span>
                                    </div>
                                    <div>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $review->rating): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="mb-0"><?php echo htmlspecialchars($review->review_text); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i> You don't have any reviews yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card mb-4 border-0 shadow-sm scroll-animate fade-up">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="<?php echo url('guide/calendar'); ?>" class="card h-100 border-0 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar fa-3x mb-3 text-primary"></i>
                                    <h5 class="card-title">Manage Calendar</h5>
                                    <p class="card-text text-muted">Set your availability and manage your schedule</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <form method="post" action="<?php echo url('guide/toggle-availability'); ?>">
                                <input type="hidden" name="current" value="<?php echo $guide->available ? 1 : 0; ?>">
                                <button type="submit" class="card h-100 text-decoration-none btn p-0 border-0 w-100"
                                    style="background:none;">
                                    <div class="card-body text-center">
                                        <i
                                            class="fas <?php echo $guide->available ? 'fa-toggle-on text-success' : 'fa-toggle-off text-danger'; ?> fa-3x mb-3"></i>
                                        <h5 class="card-title">Toggle Availability</h5>
                                        <p class="card-text text-muted">
                                            Currently: <span
                                                class="fw-bold"><?php echo $guide->available ? 'Available' : 'Not Available'; ?></span>
                                        </p>
                                    </div>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="card h-100 text-decoration-none btn p-0 border-0 w-100"
                                data-bs-toggle="modal" data-bs-target="#updateRatesModal" style="background:none;">
                                <div class="card-body text-center">
                                    <i class="fas fa-dollar-sign fa-3x mb-3 text-primary"></i>
                                    <h5 class="card-title">Update Rates</h5>
                                    <p class="card-text text-muted">Manage your hourly and daily rates</p>
                                </div>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="card h-100 text-decoration-none btn p-0 border-0 w-100"
                                data-bs-toggle="modal" data-bs-target="#updateBioModal" style="background:none;">
                                <div class="card-body text-center">
                                    <i class="fas fa-pen-to-square fa-3x mb-3 text-primary"></i>
                                    <h5 class="card-title">Update Bio</h5>
                                    <p class="card-text text-muted">Edit your guide profile description</p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Update Rates Modal -->
            <div class="modal fade" id="updateRatesModal" tabindex="-1" aria-labelledby="updateRatesModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form method="post" action="<?php echo url('guide/update-rates'); ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateRatesModalLabel">Update Rates</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="hourly_rate" class="form-label">Hourly Rate ($)</label>
                                    <input type="number" step="0.01" name="hourly_rate" id="hourly_rate"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($guide->hourly_rate ?? ''); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="daily_rate" class="form-label">Daily Rate ($)</label>
                                    <input type="number" step="0.01" name="daily_rate" id="daily_rate"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($guide->daily_rate ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn text-white rounded-pill" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">Save Rates</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Update Bio Modal -->
            <div class="modal fade" id="updateBioModal" tabindex="-1" aria-labelledby="updateBioModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form method="post" action="<?php echo url('guide/update-bio'); ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateBioModalLabel">Update Bio</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="bio" class="form-label">Bio</label>
                                    <textarea name="bio" id="bio" class="form-control" rows="5"
                                        required><?php echo htmlspecialchars($guide->bio ?? ''); ?></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn text-white rounded-pill" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">Save Bio</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>