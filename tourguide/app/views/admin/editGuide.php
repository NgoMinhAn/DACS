<!-- Admin Edit Guide -->
<div class="container py-4">
    <div class="card shadow rounded-4 mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h2 class="mb-4 fw-bold"><i class="fas fa-user-tie me-2"></i>Edit Guide</h2>
            <form method="post">
                <div class="mb-3">
                    <label class="fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($guide->name); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($guide->email); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active" <?php echo ($guide->status === 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($guide->status === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Verified</label>
                    <select name="verified" class="form-select" required>
                        <option value="1" <?php echo ($guide->verified == 1) ? 'selected' : ''; ?>>Yes</option>
                        <option value="0" <?php echo ($guide->verified == 0) ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Average Rating</label>
                    <input type="number" step="0.01" name="avg_rating" class="form-control" value="<?php echo htmlspecialchars($guide->avg_rating); ?>">
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Total Reviews</label>
                    <input type="number" name="total_reviews" class="form-control" value="<?php echo htmlspecialchars($guide->total_reviews); ?>">
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Hourly Rate ($)</label>
                    <input type="number" step="0.01" name="hourly_rate" class="form-control" value="<?php echo htmlspecialchars($guide->hourly_rate ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Daily Rate ($)</label>
                    <input type="number" step="0.01" name="daily_rate" class="form-control" value="<?php echo htmlspecialchars($guide->daily_rate ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Availability</label>
                    <select name="available" class="form-select" required>
                        <option value="1" <?php echo ($guide->available) ? 'selected' : ''; ?>>Available</option>
                        <option value="0" <?php echo (!$guide->available) ? 'selected' : ''; ?>>Unavailable</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Years of Experience</label>
                    <input type="number" name="experience_years" class="form-control" value="<?php echo htmlspecialchars($guide->experience_years ?? ''); ?>" min="0" required>
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Specialties (comma separated)</label>
                    <input type="text" name="specialties" class="form-control" value="<?php echo htmlspecialchars($guide->specialties ?? ''); ?>" placeholder="e.g. History, Food, Nature">
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Languages (comma separated)</label>
                    <input type="text" name="languages" class="form-control" value="<?php echo htmlspecialchars($guide->languages ?? ''); ?>" placeholder="e.g. English, French">
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Fluent Languages (comma separated)</label>
                    <input type="text" name="fluent_languages" class="form-control" value="<?php echo htmlspecialchars($guide->fluent_languages ?? ''); ?>" placeholder="e.g. English, French">
                    <small class="form-text text-muted">List languages in which the guide is fluent.</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Changes
                    </button>
                    <a href="<?php echo url('admin/guides'); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>