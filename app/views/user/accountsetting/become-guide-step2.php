<div class="container py-5 d-flex align-items-center justify-content-center" style="min-height: 100vh; background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);">
    <div class="col-md-10 col-lg-8">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white text-center rounded-top-4 py-4">
                <div class="mb-2">
                    <span class="badge bg-light text-primary fw-bold px-3 py-2" style="font-size:1rem;">Step 2 of 2</span>
                </div>
                <h3 class="mb-0 fw-bold"><i class="fas fa-briefcase me-2"></i>Professional Details</h3>
                <p class="mb-0 mt-2 text-white-50">Tell us about your skills and expertise</p>
            </div>
            <div class="card-body p-5">
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-semibold"><i class="fas fa-star me-2 text-primary"></i>Specialties <span class="text-danger">*</span></label>
                        <div class="row">
                            <?php foreach ($specialties as $specialty): ?>
                                <div class="col-md-4 mb-2">
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
                    <div class="mb-4">
                        <label class="form-label fw-semibold"><i class="fas fa-language me-2 text-primary"></i>Languages <span class="text-danger">*</span></label>
                        <div class="row">
                            <?php foreach ($languages as $language): ?>
                                <div class="col-md-4 mb-3">
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
                    <div class="mb-3 row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="hourly_rate" class="form-label fw-semibold"><i class="fas fa-clock me-2 text-primary"></i>Hourly Rate ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control rounded-3" id="hourly_rate" name="hourly_rate" required>
                        </div>
                        <div class="col-md-6">
                            <label for="daily_rate" class="form-label fw-semibold"><i class="fas fa-calendar-day me-2 text-primary"></i>Daily Rate ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control rounded-3" id="daily_rate" name="daily_rate" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo url('account/becomeguide?step=1'); ?>" class="btn btn-outline-secondary btn-lg rounded-pill"><i class="fas fa-arrow-left me-2"></i>Back</a>
                        <button type="submit" class="btn btn-success btn-lg rounded-pill">Submit Application <i class="fas fa-check ms-2"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 