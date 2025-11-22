<?php
/**
 * Browse Tour Guides View
 * Displays a grid of available tour guides with filtering options
 */
?>

<div class="container my-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-12 scroll-animate fade-up">
            <h1 class="display-5 fw-bold mb-3">
                <i class="fas fa-search me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
                <?php echo $title; ?>
            </h1>
            <p class="lead text-muted"><?php echo __('browse.lead'); ?></p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-5">
        <div class="col-md-3 scroll-animate fade-left">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <h5 class="mb-0 text-white fw-bold py-2">
                        <i class="fas fa-filter me-2"></i><?php echo __('filters.title'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo url('tourGuide/browse'); ?>">
                        <!-- Language Filter -->
                        <div class="mb-3">
                            <label class="form-label small"><?php echo __('filters.languages'); ?></label>
                            <select class="form-select" name="language">
                                <option value=""><?php echo __('filters.all_languages'); ?></option>
                                <?php foreach ($languages as $language): ?>
                                    <option value="<?php echo $language->code; ?>" <?php echo (isset($current_filters['language']) && $current_filters['language'] === $language->code) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($language->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Specialty Filter -->
                        <div class="mb-3">
                            <label class="form-label small"><?php echo __('filters.specialty'); ?></label>
                            <select class="form-select" name="specialty">
                                <option value=""><?php echo __('filters.all_specialties'); ?></option>
                                <?php foreach ($specialties as $specialty): ?>
                                    <option value="<?php echo $specialty->id; ?>" <?php echo (isset($current_filters['specialty']) && $current_filters['specialty'] == $specialty->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($specialty->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="mb-3">
                            <label class="form-label small"><?php echo __('filters.price_range'); ?></label>
                            <select class="form-select" name="price_range">
                                <option value=""><?php echo __('filters.any_price'); ?></option>
                                <option value="0-25" <?php echo (isset($current_filters['price_range']) && $current_filters['price_range'] === '0-25') ? 'selected' : ''; ?>><?php echo __('filters.price_under_25'); ?></option>
                                <option value="25-50" <?php echo (isset($current_filters['price_range']) && $current_filters['price_range'] === '25-50') ? 'selected' : ''; ?>><?php echo __('filters.price_25_50'); ?></option>
                                <option value="50-100" <?php echo (isset($current_filters['price_range']) && $current_filters['price_range'] === '50-100') ? 'selected' : ''; ?>><?php echo __('filters.price_50_100'); ?></option>
                                <option value="100+" <?php echo (isset($current_filters['price_range']) && $current_filters['price_range'] === '100+') ? 'selected' : ''; ?>><?php echo __('filters.price_100_plus'); ?></option>
                            </select>
                        </div>

                        <!-- Rating Filter -->
                        <div class="mb-3">
                            <label class="form-label small"><?php echo __('filters.minimum_rating'); ?></label>
                            <select class="form-select" name="rating">
                                <option value="">Any Rating</option>
                                <option value="5" <?php echo (isset($current_filters['rating']) && $current_filters['rating'] === '5') ? 'selected' : ''; ?>>5 Stars</option>
                                <option value="4" <?php echo (isset($current_filters['rating']) && $current_filters['rating'] === '4') ? 'selected' : ''; ?>>4+ Stars</option>
                                <option value="3" <?php echo (isset($current_filters['rating']) && $current_filters['rating'] === '3') ? 'selected' : ''; ?>>3+ Stars</option>
                                <option value="2" <?php echo (isset($current_filters['rating']) && $current_filters['rating'] === '2') ? 'selected' : ''; ?>>2+ Stars</option>
                            </select>
                        </div>

                        <button type="submit" class="btn w-100 text-white mb-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                            <?php echo __('filters.apply'); ?>
                        </button>
                        
                        <?php if (!empty($current_filters)): ?>
                            <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-outline-secondary w-100"><?php echo __('buttons.clear') ?? 'Clear'; ?></a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Guide Cards -->
        <div class="col-md-9">
            <!-- Recommended Section (highlighted) -->
            <?php if (!empty($recommended_guides)): ?>
                <div class="mb-5 p-4 rounded-4 shadow-lg scroll-animate fade-up" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-1 text-white fw-bold">
                                <i class="fas fa-star me-2"></i><?php echo __('browse.recommended_title'); ?>
                            </h4>
                            <small class="text-white" style="opacity: 0.8;"><?php echo __('browse.recommended_sub'); ?></small>
                        </div>
                        <div>
                                <span class="badge rounded-pill px-3 py-2" style="background: rgba(255,255,255,0.2); color: white; fs-6;">
                                <i class="fas fa-crown me-1"></i><?php echo __('browse.top_picks'); ?>
                            </span>
                        </div>
                    </div>

                    <div class="recommended-scroller d-flex gap-3 py-2" style="overflow-x: auto;">
                        <?php foreach ($recommended_guides as $rguide): ?>
                            <div class="card border-0 shadow-lg flex-shrink-0 guide-card" style="width: 280px;">
                                    <div class="position-absolute top-0 start-0 p-2" style="z-index: 2;">
                                    <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #22543d 0%, #2f855a 100%); color: white;">
                                        <i class="fas fa-crown me-1"></i><?php echo __('browse.recommended_badge'); ?>
                                    </span>
                                </div>
                                <div class="card-img-wrapper position-relative overflow-hidden">
                                    <img src="<?php echo url('assets/images/profiles/' . ($rguide->profile_image ?? 'default.jpg')); ?>" 
                                         class="card-img-top guide-card-img" alt="<?php echo htmlspecialchars($rguide->name ?? $rguide->name ?? 'Guide'); ?>"
                                         style="height: 180px; object-fit: cover;">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mb-2 text-dark fw-bold"><?php echo htmlspecialchars($rguide->name ?? ($rguide->name ?? 'Guide')); ?></h5>
                                    <div class="mb-2">
                                        <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;">
                                            <i class="fas fa-star"></i> <?php echo number_format($rguide->avg_rating ?? 0, 1); ?> (<?php echo $rguide->total_reviews ?? 0; ?>)
                                        </span>
                                    </div>
                                    <p class="card-text small mb-3 text-dark"><?php echo htmlspecialchars(substr($rguide->bio ?? 'Expert local guide', 0, 80)) . '...'; ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold" style="color: #4CAF50; font-size: 1.1rem;">$<?php echo number_format($rguide->hourly_rate ?? 0, 2); ?>/hr</span>
                                        <a href="<?php echo url('tourGuide/profile/' . ($rguide->guide_id ?? $rguide->id ?? '')); ?>" class="btn btn-sm rounded-pill px-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;"><?php echo __('buttons.view') ?? 'View'; ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- Separator between recommendations and full browse list -->
                <div class="my-4">
                    <hr class="my-0" />
                </div>
            <?php endif; ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php if (!empty($guides)): ?>
                    <?php foreach ($guides as $index => $guide): ?>
                        <div class="col scroll-animate fade-up <?php echo 'delay-' . min(($index % 5) + 1, 5); ?>">
                            <a href="<?php echo url('tourGuide/profile/' . $guide->guide_id); ?>" class="text-decoration-none">
                                <div class="card h-100 border-0 shadow-lg guide-card">
                                    <div class="card-img-wrapper position-relative overflow-hidden">
                                        <img src="<?php echo url('assets/images/profiles/' . ($guide->profile_image ?? 'default.jpg')); ?>" 
                                             class="card-img-top guide-card-img" alt="<?php echo htmlspecialchars($guide->name); ?>"
                                             style="height: 200px; object-fit: cover;">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title mb-2 text-dark fw-bold"><?php echo htmlspecialchars($guide->name); ?></h5>
                                        <div class="mb-2">
                                            <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;">
                                                <i class="fas fa-star"></i> <?php echo number_format($guide->avg_rating, 1); ?> (<?php echo $guide->total_reviews; ?>)
                                            </span>
                                        </div>
                                        <p class="card-text text-dark mb-3"><?php echo htmlspecialchars(substr($guide->bio ?? 'Expert local guide', 0, 80)) . '...'; ?></p>
                                        <p class="card-text mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1 text-primary"></i> <?php echo htmlspecialchars($guide->location ?? 'Local area'); ?>
                                            </small>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold" style="color: #4CAF50; font-size: 1.1rem;">$<?php echo number_format($guide->hourly_rate ?? 0, 2); ?>/hour</span>
                                        </div>
                                        <?php if (!empty($guide->specialties)): ?>
                                            <p class="card-text mb-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-tags me-1"></i> <?php echo htmlspecialchars($guide->specialties); ?>
                                                </small>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer bg-white border-0">
                                        <span class="btn btn-sm w-100 rounded-pill px-3" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white;"><?php echo __('buttons.view_profile'); ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                        <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info rounded-4 p-4 shadow" role="alert">
                            <i class="fas fa-info-circle me-2"></i> <?php echo __('browse.no_results'); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 