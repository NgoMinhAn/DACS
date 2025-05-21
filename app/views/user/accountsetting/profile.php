<?php flash('settings_message'); ?>

<div class="container py-5">
    <div class="row">
        <!-- Settings Navigation -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Settings</h5>
                    <div class="list-group list-group-flush">
                        <a href="<?php echo URL_ROOT; ?>/account/settings" class="list-group-item list-group-item-action d-flex align-items-center <?php echo ($section === 'general') ? 'active' : ''; ?>">
                            <i class="fas fa-cog me-2"></i> General
                        </a>
                        <a href="<?php echo URL_ROOT; ?>/account/settings/profile" class="list-group-item list-group-item-action d-flex align-items-center <?php echo ($section === 'profile') ? 'active' : ''; ?>">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                        <a href="<?php echo URL_ROOT; ?>/account/settings/password" class="list-group-item list-group-item-action d-flex align-items-center <?php echo ($section === 'password') ? 'active' : ''; ?>">
                            <i class="fas fa-lock me-2"></i> Password
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Settings Content -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">
                        <i class="fas fa-user-circle me-2 text-primary"></i>
                        Profile Settings
                    </h3>
                    
                    <form action="<?php echo URL_ROOT; ?>/account/settings/profile" method="POST" enctype="multipart/form-data">
                        <!-- Profile Image Section -->
                        <div class="mb-4 p-4 bg-light rounded text-center">
                            <div class="d-inline-block position-relative mb-3">
                                <img src="<?= URL_ROOT ?>/public/uploads/avatars/<?= $user->profile_image ?? 'default.jpg' ?>" 
                                     alt="Profile Image" 
                                     class="rounded-circle border shadow-sm"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                <label for="avatar" class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow-sm" style="cursor: pointer;">
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
                        <div class="mb-4 p-3 bg-light rounded">
                            <h5 class="d-flex align-items-center mb-3">
                                <i class="fas fa-user text-primary me-2"></i>
                                Basic Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control <?php echo (!empty($errors['name'])) ? 'is-invalid' : ''; ?>" 
                                               id="name" name="name" value="<?php echo $user->name; ?>">
                                        <div class="invalid-feedback"><?php echo $errors['name'] ?? ''; ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control <?php echo (!empty($errors['email'])) ? 'is-invalid' : ''; ?>" 
                                               id="email" name="email" value="<?php echo $user->email; ?>">
                                        <div class="invalid-feedback"><?php echo $errors['email'] ?? ''; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-4 p-3 bg-light rounded">
                            <h5 class="d-flex align-items-center mb-3">
                                <i class="fas fa-address-card text-primary me-2"></i>
                                Contact Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?php echo $user->phone ?? ''; ?>"
                                               placeholder="+1 (234) 567-8900">
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <textarea class="form-control" id="address" name="address" rows="3" 
                                                  placeholder="Enter your full address"><?php echo $user->address ?? ''; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($user->user_type === 'guide'): ?>
                        <!-- Guide Information -->
                        <div class="mb-4 p-3 bg-light rounded">
                            <h5 class="d-flex align-items-center mb-3">
                                <i class="fas fa-map text-primary me-2"></i>
                                Guide Information
                            </h5>
                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                    <textarea class="form-control" id="bio" name="bio" rows="4" 
                                              placeholder="Tell visitors about yourself and your experience as a tour guide"><?php echo $user->bio ?? ''; ?></textarea>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Share your experience and what makes you unique as a tour guide.
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="languages" class="form-label">Languages</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-language"></i></span>
                                        <input type="text" class="form-control" id="languages" name="languages" 
                                               value="<?php echo $user->languages ?? ''; ?>"
                                               placeholder="e.g. English, Spanish, French">
                                    </div>
                                    <div class="form-text">Separate languages with commas</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="specialties" class="form-label">Specialties</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-star"></i></span>
                                        <input type="text" class="form-control" id="specialties" name="specialties" 
                                               value="<?php echo $user->specialties ?? ''; ?>"
                                               placeholder="e.g. Historical Tours, Food Tours">
                                    </div>
                                    <div class="form-text">Separate specialties with commas</div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
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