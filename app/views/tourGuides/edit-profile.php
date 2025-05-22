<!-- Edit Guide Profile -->
<div class="container py-4">
    <h2>Edit Profile</h2>
    <form method="post" action="">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($guide->name ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="bio" class="form-label">Bio</label>
            <textarea name="bio" id="bio" class="form-control" rows="4" required><?php echo htmlspecialchars($guide->bio ?? ''); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="specialties" class="form-label">Specialties (comma separated)</label>
            <input type="text" name="specialties" id="specialties" class="form-control" value="<?php echo htmlspecialchars($guide->specialties ?? ''); ?>" placeholder="e.g. History, Food, Nature">
        </div>
        <div class="mb-3">
            <label for="languages" class="form-label">Languages (comma separated)</label>
            <input type="text" name="languages" id="languages" class="form-control" value="<?php echo htmlspecialchars($guide->languages ?? ''); ?>" placeholder="e.g. English, French">
        </div>
        <div class="mb-3">
            <label for="hourly_rate" class="form-label">Hourly Rate ($)</label>
            <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" class="form-control" value="<?php echo htmlspecialchars($guide->hourly_rate ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="daily_rate" class="form-label">Daily Rate ($)</label>
            <input type="number" step="0.01" name="daily_rate" id="daily_rate" class="form-control" value="<?php echo htmlspecialchars($guide->daily_rate ?? ''); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="<?php echo url('guide/dashboard'); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>