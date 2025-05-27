<div class="container py-5">
    <h2>Apply to Become a Guide</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="mb-3">
            <label for="profile_image" class="form-label">Profile Image</label>
            <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
        </div>
        <div class="mb-3">
            <label for="certifications" class="form-label">Certifications</label>
            <textarea class="form-control" id="certifications" name="certifications" rows="2"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Specialties</label>
            <div class="row">
                <?php foreach ($specialties as $specialty): ?>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="specialties[]" value="<?php echo htmlspecialchars($specialty->name); ?>" id="specialty_<?php echo $specialty->id; ?>">
                            <label class="form-check-label" for="specialty_<?php echo $specialty->id; ?>">
                                <?php echo htmlspecialchars($specialty->name); ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Languages</label>
            <div class="row">
                <?php foreach ($languages as $language): ?>
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="languages[]" value="<?php echo htmlspecialchars($language->name); ?>" id="lang_<?php echo $language->id; ?>">
                            <label class="form-check-label" for="lang_<?php echo $language->id; ?>">
                                <?php echo htmlspecialchars($language->name); ?>
                            </label>
                        </div>
                        <select class="form-select mt-1" name="languages_fluency[]">
                            <option value="basic">Basic</option>
                            <option value="conversational">Conversational</option>
                            <option value="fluent">Fluent</option>
                            <option value="native">Native</option>
                        </select>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="mb-3">
            <label for="hourly_rate" class="form-label">Hourly Rate ($)</label>
            <input type="number" step="0.01" class="form-control" id="hourly_rate" name="hourly_rate" required>
        </div>
        <div class="mb-3">
            <label for="daily_rate" class="form-label">Daily Rate ($)</label>
            <input type="number" step="0.01" class="form-control" id="daily_rate" name="daily_rate" required>
        </div>
        <div class="mb-3">
            <label for="bio" class="form-label">Bio</label>
            <textarea class="form-control" id="bio" name="bio" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label for="experience" class="form-label">Experience</label>
            <textarea class="form-control" id="experience" name="experience" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit Application</button>
    </form>
</div> 