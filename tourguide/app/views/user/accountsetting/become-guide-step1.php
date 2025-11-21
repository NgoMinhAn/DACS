<div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 100vh; background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white text-center rounded-top-4 py-4">
                <div class="mb-2">
                    <span class="badge bg-light text-primary fw-bold px-3 py-2" style="font-size:1rem;">Step 1 of 2</span>
                </div>
                <h3 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i>Personal Information</h3>
                <p class="mb-0 mt-2 text-white-50">Tell us about yourself to get started</p>
            </div>
            <div class="card-body p-5">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="location" class="form-label fw-semibold"><i class="fas fa-map-marker-alt me-2 text-primary"></i><?php echo __('search.location'); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg rounded-3" id="location" name="location" required>
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="form-label fw-semibold"><i class="fas fa-phone me-2 text-primary"></i><?php echo __('contact.phone_number'); ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg rounded-3" id="phone" name="phone" required>
                    </div>
                    <div class="mb-4">
                        <label for="profile_image" class="form-label fw-semibold"><i class="fas fa-image me-2 text-primary"></i>Profile Image</label>
                        <input type="file" class="form-control form-control-lg rounded-3" id="profile_image" name="profile_image" accept="image/*">
                    </div>
                    <div class="mb-4">
                        <label for="certifications" class="form-label fw-semibold"><i class="fas fa-certificate me-2 text-primary"></i>Certifications</label>
                        <textarea class="form-control rounded-3" id="certifications" name="certifications" rows="2"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="bio" class="form-label fw-semibold"><i class="fas fa-info-circle me-2 text-primary"></i>Bio <span class="text-danger">*</span></label>
                        <textarea class="form-control rounded-3" id="bio" name="bio" rows="4" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="experience" class="form-label fw-semibold"><i class="fas fa-briefcase me-2 text-primary"></i>Experience</label>
                        <textarea class="form-control rounded-3" id="experience" name="experience" rows="3"></textarea>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow"><span class="fw-bold"><?php echo __('buttons.next'); ?></span> <i class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>