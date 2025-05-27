<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">Step 2: Professional Details</h3>
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label">Specialties <span class="text-danger">*</span></label>
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
                        <div class="mb-4">
                            <label class="form-label">Languages <span class="text-danger">*</span></label>
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
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="hourly_rate" class="form-label">Hourly Rate ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="hourly_rate" name="hourly_rate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="daily_rate" class="form-label">Daily Rate ($) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="daily_rate" name="daily_rate" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo url('account/becomeguide?step=1'); ?>" class="btn btn-outline-secondary btn-lg"><i class="fas fa-arrow-left me-2"></i>Back</a>
                            <button type="submit" class="btn btn-success btn-lg">Submit Application <i class="fas fa-check ms-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 