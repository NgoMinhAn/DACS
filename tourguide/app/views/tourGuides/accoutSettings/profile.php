<!-- Guide Profile Settings Page -->
<div class="container py-5 flex-grow-1" style="max-width: 1000px;">
    <div class="row">
        <!-- Settings Navigation -->
        <div class="col-md-3">
            <?php include __DIR__ . '/settings_nav.php'; ?>
        </div>
        <!-- Profile Settings Content -->
        <div class="col-md-9">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4">
                    <h3 class="card-title mb-4 fw-bold">
                        <i class="fas fa-user-circle me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        Profile Settings
                    </h3>
                    <?php if (isset($profile_message)): ?>
                        <div class="alert alert-info"><?php echo $profile_message; ?></div>
                    <?php endif; ?>

                    <form method="post" action="" enctype="multipart/form-data">
                        <!-- Profile Image Section -->
                        <div class="mb-4 p-4 rounded-4 shadow-sm text-center" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <div class="d-inline-block position-relative mb-3">
                                <img src="<?php echo url('assets/images/profiles/' . ($guide->profile_image ?? 'default.jpg')); ?>"
                                     alt="Profile Image"
                                     class="rounded-circle border shadow-sm"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                <label for="profile_image" class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow-sm" style="cursor: pointer;">
                                    <i class="fas fa-camera text-primary"></i>
                                </label>
                                <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/*">
                            </div>
                            <div class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Allowed formats: JPG, JPEG, PNG, GIF (Max: 5MB)
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="mb-4 p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <h5 class="d-flex align-items-center mb-3">
                                <i class="fas fa-user text-primary me-2"></i>
                                Basic Information
                            </h5>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="<?php echo htmlspecialchars($guide->name ?? ''); ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Guide Information -->
                        <div class="mb-4 p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <h5 class="d-flex align-items-center mb-3">
                                <i class="fas fa-map text-primary me-2"></i>
                                Guide Information
                            </h5>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                    <textarea class="form-control" id="bio" name="bio" rows="4"
                                              placeholder="Tell visitors about yourself and your experience as a tour guide" required><?php echo htmlspecialchars($guide->bio ?? ''); ?></textarea>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Share your experience and what makes you unique as a tour guide.
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" id="location" name="location"
                                               value="<?php echo htmlspecialchars($guide->location ?? ''); ?>">
                                    </div>
                                </div>
                                <!-- Add more guide-specific fields here if needed -->
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-save me-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Profile Image Preview -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileInput = document.getElementById('profile_image');
    if (profileInput) {
        const profileImage = profileInput.parentElement.querySelector('img');
        profileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
});
</script>