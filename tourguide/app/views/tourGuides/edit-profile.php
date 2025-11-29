<!-- Edit Guide Profile -->
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg scroll-animate fade-up">
                <div class="card-header border-0 pb-0" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <h2 class="mb-0 text-white fw-bold">
                        <i class="fas fa-user-edit me-2"></i><?php echo __('profile.edit_profile_title'); ?>
                    </h2>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="" enctype="multipart/form-data">
                        <!-- Profile Image Upload -->
                        <div class="mb-4 text-center">
                            <label class="form-label fw-bold"><?php echo __('profile.profile_picture'); ?></label>
                            <div class="mb-3">
                                <div class="position-relative d-inline-block">
                                    <img id="profileImagePreview" 
                                         src="<?php echo url('assets/images/profiles/' . ($guide->profile_image ?? 'default.jpg')); ?>" 
                                         alt="Profile Image" 
                                         class="rounded-circle"
                                         style="width: 200px; height: 200px; object-fit: cover; border: 5px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.2); cursor: pointer;">
                                    <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-3" style="border: 3px solid white; cursor: pointer;" onclick="document.getElementById('profile_image').click();">
                                        <i class="fas fa-camera text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <input type="file" name="profile_image" id="profile_image" accept="image/*" class="d-none" onchange="previewImage(this)">
                            <small class="text-muted d-block"><?php echo __('profile.click_camera_upload'); ?></small>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">
                                <i class="fas fa-user me-2 text-primary"></i><?php echo __('profile.full_name'); ?>
                            </label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg" value="<?php echo htmlspecialchars($guide->name ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i><?php echo __('profile.location'); ?>
                            </label>
                            <input type="text" name="location" id="location" class="form-control form-control-lg" value="<?php echo htmlspecialchars($guide->location ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="bio" class="form-label fw-bold">
                                <i class="fas fa-file-alt me-2 text-primary"></i><?php echo __('profile.bio_label'); ?>
                            </label>
                            <textarea name="bio" id="bio" class="form-control" rows="6" required><?php echo htmlspecialchars($guide->bio ?? ''); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-star me-2 text-primary"></i><?php echo __('profile.specialties'); ?>
                            </label>
                            <div class="row p-3 rounded" style="background-color: var(--warm-cream);">
                                <?php foreach ($all_specialties as $specialty): ?>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="specialties[]" value="<?php echo htmlspecialchars($specialty->name); ?>" id="specialty_<?php echo $specialty->id; ?>" <?php echo in_array($specialty->name, $selected_specialties) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="specialty_<?php echo $specialty->id; ?>">
                                                <?php echo htmlspecialchars($specialty->name); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="languages" class="form-label fw-bold">
                                <i class="fas fa-language me-2 text-primary"></i><?php echo __('profile.languages_hint'); ?>
                            </label>
                            <input type="text" name="languages" id="languages" class="form-control form-control-lg" value="<?php echo htmlspecialchars($guide->languages ?? ''); ?>" placeholder="<?php echo __('profile.languages_placeholder'); ?>">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                    <label for="hourly_rate" class="form-label fw-bold">
                                    <i class="fas fa-clock me-2 text-primary"></i><?php echo __('profile.hourly_rate'); ?> ($)
                                </label>
                                <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" class="form-control form-control-lg" value="<?php echo htmlspecialchars($guide->hourly_rate ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="daily_rate" class="form-label fw-bold">
                                    <i class="fas fa-calendar-day me-2 text-primary"></i><?php echo __('profile.daily_rate'); ?> ($)
                                </label>
                                <input type="number" step="0.01" name="daily_rate" id="daily_rate" class="form-control form-control-lg" value="<?php echo htmlspecialchars($guide->daily_rate ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-lg shadow text-white flex-fill" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                <i class="fas fa-save me-2"></i><?php echo __('buttons.save_changes') ?? 'Save Changes'; ?>
                            </button>
                            <a href="<?php echo url('guide/dashboard'); ?>" class="btn btn-lg btn-outline-secondary flex-fill">
                                <i class="fas fa-times me-2"></i><?php echo __('buttons.cancel'); ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profileImagePreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>