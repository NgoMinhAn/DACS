<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold">
                <i class="fas fa-user-edit me-2 text-primary"></i>Edit Guide
            </h2>
            <p class="text-muted mb-0">Update guide information and settings</p>
        </div>
        <a href="<?php echo url('admin/guides'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Guides
        </a>
    </div>

    <form method="post">
        <div class="row">
            <!-- Left Column - Basic Information -->
            <div class="col-lg-6">
                <!-- Basic Information Card -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-user me-2"></i>Basic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user me-2 text-primary"></i>Name
                            </label>
                            <input type="text" name="name" class="form-control form-control-lg" 
                                   value="<?php echo htmlspecialchars($guide->name); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-envelope me-2 text-primary"></i>Email
                            </label>
                            <input type="email" name="email" class="form-control form-control-lg" 
                                   value="<?php echo htmlspecialchars($guide->email); ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-toggle-on me-2 text-primary"></i>Status
                                </label>
                                <select name="status" class="form-select form-select-lg" required>
                                    <option value="active" <?php echo ($guide->status === 'active') ? 'selected' : ''; ?>>
                                        <i class="fas fa-check-circle"></i> Active
                                    </option>
                                    <option value="inactive" <?php echo ($guide->status === 'inactive') ? 'selected' : ''; ?>>
                                        <i class="fas fa-times-circle"></i> Inactive
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-shield-alt me-2 text-primary"></i>Verified
                                </label>
                                <select name="verified" class="form-select form-select-lg" required>
                                    <option value="1" <?php echo ($guide->verified == 1) ? 'selected' : ''; ?>>Yes</option>
                                    <option value="0" <?php echo ($guide->verified == 0) ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>Years of Experience
                                </label>
                                <input type="number" name="experience_years" class="form-control form-control-lg" 
                                       value="<?php echo htmlspecialchars($guide->experience_years ?? 0); ?>" 
                                       min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-toggle-on me-2 text-primary"></i>Availability
                                </label>
                                <select name="available" class="form-select form-select-lg" required>
                                    <option value="1" <?php echo ($guide->available) ? 'selected' : ''; ?>>Available</option>
                                    <option value="0" <?php echo (!$guide->available) ? 'selected' : ''; ?>>Unavailable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ratings & Reviews Card -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-warning text-dark py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-star me-2"></i>Ratings & Reviews
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-star-half-alt me-2 text-warning"></i>Average Rating
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="number" step="0.01" name="avg_rating" class="form-control" 
                                           value="<?php echo htmlspecialchars($guide->avg_rating ?? 0); ?>" 
                                           min="0" max="5">
                                    <span class="input-group-text">/ 5.0</span>
                                </div>
                                <small class="form-text text-muted">Rating from 0.00 to 5.00</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-comments me-2 text-warning"></i>Total Reviews
                                </label>
                                <input type="number" name="total_reviews" class="form-control form-control-lg" 
                                       value="<?php echo htmlspecialchars($guide->total_reviews ?? 0); ?>" 
                                       min="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Pricing & Details -->
            <div class="col-lg-6">
                <!-- Pricing Card -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-dollar-sign me-2"></i>Pricing
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-clock me-2 text-success"></i>Hourly Rate ($)
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">$</span>
                                <input type="number" step="0.01" name="hourly_rate" class="form-control" 
                                       value="<?php echo htmlspecialchars($guide->hourly_rate ?? 0); ?>" 
                                       min="0" placeholder="0.00">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar-day me-2 text-success"></i>Daily Rate ($)
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">$</span>
                                <input type="number" step="0.01" name="daily_rate" class="form-control" 
                                       value="<?php echo htmlspecialchars($guide->daily_rate ?? 0); ?>" 
                                       min="0" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Specialties & Languages Card -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-info text-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-tags me-2"></i>Specialties & Languages
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-star me-2 text-info"></i>Specialties
                            </label>
                            <input type="text" name="specialties" class="form-control form-control-lg" 
                                   value="<?php echo htmlspecialchars($guide->specialties ?? ''); ?>" 
                                   placeholder="e.g. History, Food, Nature, Adventure">
                            <small class="form-text text-muted">Separate multiple specialties with commas</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-language me-2 text-info"></i>Languages
                            </label>
                            <input type="text" name="languages" class="form-control form-control-lg" 
                                   value="<?php echo htmlspecialchars($guide->languages ?? ''); ?>" 
                                   placeholder="e.g. English, French, Spanish">
                            <small class="form-text text-muted">Separate multiple languages with commas</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-check-circle me-2 text-info"></i>Fluent Languages
                            </label>
                            <input type="text" name="fluent_languages" class="form-control form-control-lg" 
                                   value="<?php echo htmlspecialchars($guide->fluent_languages ?? ''); ?>" 
                                   placeholder="e.g. English, French">
                            <small class="form-text text-muted">Languages in which the guide is fluent (comma separated)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex gap-3 justify-content-end">
                    <a href="<?php echo url('admin/guides'); ?>" class="btn btn-outline-secondary btn-lg px-5">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.form-label {
    color: #495057;
    margin-bottom: 0.5rem;
}

.input-group-text {
    font-weight: 600;
}
</style>
