<?php
/**
 * Register Guide View
 * Multi-step guide registration process
 */
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-9">
            <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);">
                    <h4 class="m-0 fw-bold"><i class="fas fa-user-tie me-2"></i><?php echo __('guide.become_guide_title'); ?></h4>
                    <p class="mb-0 mt-2"><?php echo __('guide.become_guide_subtitle'); ?></p>
                </div>

                <!-- Progress Bar -->
                <div class="px-4 pt-4">
                    <div class="progress mb-4" style="height: 8px;">
                        <?php
                        $progress = 33;
                        if (isset($step)) {
                            switch ($step) {
                                case 'basic': $progress = 33; break;
                                case 'profile': $progress = 66; break;
                                case 'complete': $progress = 100; break;
                            }
                        }
                        ?>
                        <div class="progress-bar" role="progressbar" style="width: <?php echo $progress; ?>%; background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%);" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="d-flex justify-content-between mb-4">
                        <span class="badge <?php echo (isset($step) && $step == 'basic') || !isset($step) ? 'bg-primary' : 'bg-secondary'; ?>"><?php echo __('guide.step_basic'); ?></span>
                        <span class="badge <?php echo isset($step) && $step == 'profile' ? 'bg-primary' : 'bg-secondary'; ?>"><?php echo __('guide.step_profile'); ?></span>
                        <span class="badge <?php echo isset($step) && $step == 'complete' ? 'bg-primary' : 'bg-secondary'; ?>"><?php echo __('guide.step_complete'); ?></span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Display Flash Messages -->
                    <?php flash('guide_message'); ?>

                    <?php if (!isset($step) || $step == 'basic'): ?>
                        <!-- Step 1: Basic Information -->
                        <h5 class="mb-4 text-center"><?php echo __('guide.step_basic_title'); ?></h5>
                        <form action="<?php echo url('account/becomeguide'); ?>" method="POST">
                            <input type="hidden" name="step" value="basic">

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold"><?php echo __('auth.full_name'); ?> *</label>
                                    <div class="input-group" style="border-radius: 10px;">
                                        <span class="input-group-text" style="background-color: #f5f5f5; border: 2px solid #e0e0e0; border-right: none;"><i class="fas fa-user text-primary"></i></span>
                                        <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>"
                                               id="name" name="name" value="<?php echo $name ?? ''; ?>"
                                               style="border: 2px solid #e0e0e0; border-left: none; border-radius: 10px;"
                                               placeholder="<?php echo __('auth.full_name_placeholder'); ?>" required>
                                        <?php if (isset($errors['name'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold"><?php echo __('auth.email'); ?> *</label>
                                    <div class="input-group" style="border-radius: 10px;">
                                        <span class="input-group-text" style="background-color: #f5f5f5; border: 2px solid #e0e0e0; border-right: none;"><i class="fas fa-envelope text-primary"></i></span>
                                        <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                                               id="email" name="email" value="<?php echo $email ?? ''; ?>"
                                               style="border: 2px solid #e0e0e0; border-left: none; border-radius: 10px;"
                                               placeholder="<?php echo __('auth.email_placeholder'); ?>" required>
                                        <?php if (isset($errors['email'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-semibold"><?php echo __('auth.phone'); ?> *</label>
                                    <div class="input-group" style="border-radius: 10px;">
                                        <span class="input-group-text" style="background-color: #f5f5f5; border: 2px solid #e0e0e0; border-right: none;"><i class="fas fa-phone text-primary"></i></span>
                                        <input type="tel" class="form-control <?php echo isset($errors['phone']) ? 'is-invalid' : ''; ?>"
                                               id="phone" name="phone" value="<?php echo $phone ?? ''; ?>"
                                               style="border: 2px solid #e0e0e0; border-left: none; border-radius: 10px;"
                                               placeholder="<?php echo __('auth.phone_placeholder'); ?>" required>
                                        <?php if (isset($errors['phone'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['phone']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="location" class="form-label fw-semibold"><?php echo __('guide.location'); ?> *</label>
                                    <div class="input-group" style="border-radius: 10px;">
                                        <span class="input-group-text" style="background-color: #f5f5f5; border: 2px solid #e0e0e0; border-right: none;"><i class="fas fa-map-marker-alt text-primary"></i></span>
                                        <input type="text" class="form-control <?php echo isset($errors['location']) ? 'is-invalid' : ''; ?>"
                                               id="location" name="location" value="<?php echo $location ?? ''; ?>"
                                               style="border: 2px solid #e0e0e0; border-left: none; border-radius: 10px;"
                                               placeholder="<?php echo __('guide.location_placeholder'); ?>" required>
                                        <?php if (isset($errors['location'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['location']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-lg px-5" style="background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%); color: white; border: none; border-radius: 25px;">
                                    <i class="fas fa-arrow-right me-2"></i><?php echo __('guide.continue_to_profile'); ?>
                                </button>
                            </div>
                        </form>

                    <?php elseif (isset($step) && $step == 'profile'): ?>
                        <!-- Step 2: Profile Information -->
                        <h5 class="mb-4 text-center"><?php echo __('guide.step_profile_title'); ?></h5>
                        <form action="<?php echo url('account/becomeguide'); ?>" method="POST">
                            <input type="hidden" name="step" value="profile">

                            <div class="mb-4">
                                <label for="bio" class="form-label fw-semibold"><?php echo __('guide.bio'); ?> *</label>
                                <textarea class="form-control <?php echo isset($errors['bio']) ? 'is-invalid' : ''; ?>"
                                          id="bio" name="bio" rows="4"
                                          style="border: 2px solid #e0e0e0; border-radius: 10px;"
                                          placeholder="<?php echo __('guide.bio_placeholder'); ?>" required><?php echo $bio ?? ''; ?></textarea>
                                <?php if (isset($errors['bio'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['bio']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="hourly_rate" class="form-label fw-semibold"><?php echo __('guide.hourly_rate'); ?> *</label>
                                    <div class="input-group" style="border-radius: 10px;">
                                        <span class="input-group-text" style="background-color: #f5f5f5; border: 2px solid #e0e0e0; border-right: none;">$</span>
                                        <input type="number" class="form-control <?php echo isset($errors['hourly_rate']) ? 'is-invalid' : ''; ?>"
                                               id="hourly_rate" name="hourly_rate" value="<?php echo $hourly_rate ?? ''; ?>"
                                               style="border: 2px solid #e0e0e0; border-left: none; border-radius: 10px;"
                                               placeholder="50" min="10" required>
                                        <?php if (isset($errors['hourly_rate'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['hourly_rate']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="daily_rate" class="form-label fw-semibold"><?php echo __('guide.daily_rate'); ?> *</label>
                                    <div class="input-group" style="border-radius: 10px;">
                                        <span class="input-group-text" style="background-color: #f5f5f5; border: 2px solid #e0e0e0; border-right: none;">$</span>
                                        <input type="number" class="form-control <?php echo isset($errors['daily_rate']) ? 'is-invalid' : ''; ?>"
                                               id="daily_rate" name="daily_rate" value="<?php echo $daily_rate ?? ''; ?>"
                                               style="border: 2px solid #e0e0e0; border-left: none; border-radius: 10px;"
                                               placeholder="200" min="50" required>
                                        <?php if (isset($errors['daily_rate'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['daily_rate']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-lg px-5" style="background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%); color: white; border: none; border-radius: 25px;">
                                    <i class="fas fa-arrow-right me-2"></i><?php echo __('guide.continue_to_languages'); ?>
                                </button>
                            </div>
                        </form>

                    <?php elseif (isset($step) && $step == 'complete'): ?>
                        <!-- Step 3: Languages and Specialties -->
                        <h5 class="mb-4 text-center"><?php echo __('guide.step_complete_title'); ?></h5>
                        <form action="<?php echo url('account/becomeguide'); ?>" method="POST">
                            <input type="hidden" name="step" value="complete">

                            <div class="mb-4">
                                <label class="form-label fw-semibold"><?php echo __('guide.languages'); ?> *</label>
                                <div class="row">
                                    <?php
                                    $languages = ['English', 'Spanish', 'French', 'German', 'Italian', 'Chinese', 'Japanese', 'Korean', 'Vietnamese'];
                                    foreach ($languages as $lang):
                                    ?>
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="languages[]" value="<?php echo $lang; ?>" id="lang_<?php echo $lang; ?>">
                                                <label class="form-check-label" for="lang_<?php echo $lang; ?>">
                                                    <?php echo $lang; ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold"><?php echo __('guide.specialties'); ?> *</label>
                                <div class="row">
                                    <?php
                                    $specialties = ['City Tours', 'Historical Sites', 'Nature & Outdoors', 'Food & Wine', 'Adventure', 'Cultural Experiences', 'Photography Tours', 'Family Friendly'];
                                    foreach ($specialties as $spec):
                                    ?>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="specialties[]" value="<?php echo $spec; ?>" id="spec_<?php echo str_replace(' ', '_', $spec); ?>">
                                                <label class="form-check-label" for="spec_<?php echo str_replace(' ', '_', $spec); ?>">
                                                    <?php echo $spec; ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-lg px-5" style="background: linear-gradient(135deg, #FF6B35 0%, #F7931E 100%); color: white; border: none; border-radius: 25px;">
                                    <i class="fas fa-check me-2"></i><?php echo __('guide.complete_registration'); ?>
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>

                    <div class="text-center mt-4">
                        <p class="mb-0"><?php echo __('guide.already_have_account'); ?> <a href="<?php echo url('account/login'); ?>" class="text-decoration-none"><?php echo __('auth.login'); ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>