<?php flash('settings_message'); ?>

<div class="container py-5">
    <div class="row">
        <!-- Settings Navigation -->
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body">
                    <h5 class="card-title mb-4 fw-bold">
                        <i class="fas fa-cog me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        Settings
                    </h5>
                    <div class="list-group list-group-flush">
                        <a href="<?php echo url('account/settings'); ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 mb-2 <?php echo ($section === 'general') ? 'active' : ''; ?>"
                           style="<?php echo ($section === 'general') ? 'background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white; border: none;' : ''; ?>">
                            <i class="fas fa-cog me-2"></i> General
                        </a>
                        <a href="<?php echo url('account/settings/profile'); ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 mb-2 <?php echo ($section === 'profile') ? 'active' : ''; ?>"
                           style="<?php echo ($section === 'profile') ? 'background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white; border: none;' : ''; ?>">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                        <a href="<?php echo url('account/settings/password'); ?>" class="list-group-item list-group-item-action d-flex align-items-center rounded-3 <?php echo ($section === 'password') ? 'active' : ''; ?>"
                           style="<?php echo ($section === 'password') ? 'background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white; border: none;' : ''; ?>">
                            <i class="fas fa-lock me-2"></i> Password
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Settings Content -->
        <div class="col-md-9">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4">
                    <h3 class="card-title mb-4 fw-bold">
                        <i class="fas fa-user-circle me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        Profile Settings
                    </h3>
                    
                    <form action="<?php echo url('account/settings/profile'); ?>" method="POST" enctype="multipart/form-data">
                        <!-- Profile Image Section -->
                        <div class="mb-4 p-4 rounded-4 shadow-sm text-center" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <div class="d-inline-block position-relative mb-3">
                                <img src="<?php echo url('public/uploads/avatars/' . ($user->profile_image ?? 'default.jpg')); ?>" 
                                     alt="Profile Image" 
                                     class="rounded-circle border shadow-lg"
                                     style="width: 150px; height: 150px; object-fit: cover; border-width: 4px !important;">
                                <label for="avatar" class="position-absolute bottom-0 end-0 bg-white rounded-circle p-3 shadow-lg" style="cursor: pointer; border: 3px solid #4a5568;">
                                    <i class="fas fa-camera text-primary"></i>
                                </label>
                                <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                            </div>
                            <div class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Allowed formats: JPG, JPEG, PNG, GIF (Max: 5MB)
                            </div>
                            <?php if (isset($errors['avatar'])) : ?>
                                <div class="text-danger mt-2"><?= $errors['avatar'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Basic Information -->
                        <div class="mb-4 p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <h5 class="d-flex align-items-center mb-4 fw-bold">
                                <div class="rounded-circle p-2 me-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                Basic Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-semibold">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control rounded-end <?php echo (!empty($errors['name'])) ? 'is-invalid' : ''; ?>" 
                                               id="name" name="name" value="<?php echo htmlspecialchars($user->name); ?>">
                                        <div class="invalid-feedback"><?php echo $errors['name'] ?? ''; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-semibold">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control rounded-end <?php echo (!empty($errors['email'])) ? 'is-invalid' : ''; ?>" 
                                               id="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>">
                                        <div class="invalid-feedback"><?php echo $errors['email'] ?? ''; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-4 p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <h5 class="d-flex align-items-center mb-4 fw-bold">
                                <div class="rounded-circle p-2 me-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                    <i class="fas fa-address-card text-white"></i>
                                </div>
                                Contact Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control rounded-end" id="phone" name="phone" 
                                               value="<?php echo htmlspecialchars($user->phone ?? ''); ?>"
                                               placeholder="+1 (234) 567-8900">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label fw-semibold">Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start"><i class="fas fa-map-marker-alt"></i></span>
                                        <textarea class="form-control rounded-end" id="address" name="address" rows="3" 
                                                  placeholder="Enter your full address"><?php echo htmlspecialchars($user->address ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hobbies & Tourism Interests -->
                        <div class="mb-4 p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <h5 class="d-flex align-items-center mb-4 fw-bold">
                                <div class="rounded-circle p-2 me-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                    <i class="fas fa-heart text-white"></i>
                                </div>
                                Hobbies & Tourism Interests
                            </h5>
                            <div class="mb-3">
                                <label for="hobbies" class="form-label fw-semibold">Your hobbies and interests related to tourism</label>
                                <textarea class="form-control rounded-3" id="hobbies" name="hobbies" rows="3" placeholder="e.g. Hiking, Food tours, Museums, Adventure sports, Local culture, Nature walks, Photography, etc."><?php echo htmlspecialchars($user->hobbies ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    List your hobbies and interests to get better tour guide recommendations.
                                </div>
                            </div>
                        </div>

                        <?php if ($user->user_type === 'guide'): ?>
                        <!-- Guide Information -->
                        <div class="mb-4 p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <h5 class="d-flex align-items-center mb-4 fw-bold">
                                <div class="rounded-circle p-2 me-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                    <i class="fas fa-map text-white"></i>
                                </div>
                                Guide Information
                            </h5>
                            <div class="mb-3">
                                <label for="bio" class="form-label fw-semibold">Bio</label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start"><i class="fas fa-pen"></i></span>
                                    <textarea class="form-control rounded-end" id="bio" name="bio" rows="4" 
                                              placeholder="Tell visitors about yourself and your experience as a tour guide"><?php echo htmlspecialchars($user->bio ?? ''); ?></textarea>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Share your experience and what makes you unique as a tour guide.
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="languages" class="form-label fw-semibold">Languages</label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start"><i class="fas fa-language"></i></span>
                                        <input type="text" class="form-control rounded-end" id="languages" name="languages" 
                                               value="<?php echo htmlspecialchars($user->languages ?? ''); ?>"
                                               placeholder="e.g. English, Spanish, French">
                                    </div>
                                    <div class="form-text">Separate languages with commas</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="specialties" class="form-label fw-semibold">Specialties</label>
                                    <div class="input-group">
                                        <span class="input-group-text rounded-start"><i class="fas fa-star"></i></span>
                                        <input type="text" class="form-control rounded-end" id="specialties" name="specialties" 
                                               value="<?php echo htmlspecialchars($user->specialties ?? ''); ?>"
                                               placeholder="e.g. Historical Tours, Food Tours">
                                    </div>
                                    <div class="form-text">Separate specialties with commas</div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn rounded-pill px-5 text-white" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
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
    const avatarInput = document.getElementById('avatar');
    const avatarImage = avatarInput.parentElement.querySelector('img');
    
    avatarInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                avatarImage.src = e.target.result;
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
});
</script>
