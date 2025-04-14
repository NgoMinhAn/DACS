<?php
/**
 * Browse Tour Guides View
 * Displays a grid of available tour guides with filtering options
 */
?>

<div class="container my-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4"><?php echo $title; ?></h1>
            <p class="lead">Find your perfect guide for an unforgettable experience</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filters</h5>
                    <form>
                        <!-- Language Filter -->
                        <div class="mb-3">
                            <label class="form-label">Languages</label>
                            <select class="form-select" name="language">
                                <option value="">All Languages</option>
                                <option value="english">English</option>
                                <option value="vietnamese">Vietnamese</option>
                                <option value="japanese">Japanese</option>
                                <option value="korean">Korean</option>
                            </select>
                        </div>

                        <!-- Specialty Filter -->
                        <div class="mb-3">
                            <label class="form-label">Specialty</label>
                            <select class="form-select" name="specialty">
                                <option value="">All Specialties</option>
                                <option value="history">History & Culture</option>
                                <option value="food">Food & Cuisine</option>
                                <option value="adventure">Adventure</option>
                                <option value="nature">Nature & Wildlife</option>
                            </select>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="mb-3">
                            <label class="form-label">Price Range (per hour)</label>
                            <select class="form-select" name="price_range">
                                <option value="">Any Price</option>
                                <option value="0-25">Under $25</option>
                                <option value="25-50">$25 - $50</option>
                                <option value="50-100">$50 - $100</option>
                                <option value="100+">$100+</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Guide Cards -->
        <div class="col-md-9">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php if (!empty($guides)): ?>
                    <?php foreach ($guides as $guide): ?>
                        <div class="col">
                            <div class="card h-100">
                                <img src="<?php echo isset($guide['image']) ? $guide['image'] : '/assets/images/default_guide.jpg'; ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($guide['name']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($guide['name']); ?></h5>
                                    <div class="mb-2">
                                        <span class="text-warning">
                                            <?php for($i = 0; $i < floor($guide['rating'] ?? 0); $i++): ?>
                                                <i class="fas fa-star"></i>
                                            <?php endfor; ?>
                                            <?php if (($guide['rating'] ?? 0) - floor($guide['rating'] ?? 0) > 0): ?>
                                                <i class="fas fa-star-half-alt"></i>
                                            <?php endif; ?>
                                        </span>
                                        <small class="text-muted">(<?php echo $guide['reviews'] ?? 0; ?> reviews)</small>
                                    </div>
                                    <p class="card-text"><?php echo htmlspecialchars(substr($guide['bio'] ?? '', 0, 100)) . '...'; ?></p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-tag"></i> $<?php echo number_format($guide['hourly_rate'] ?? 0); ?>/hour
                                        </small>
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="/tourGuide/profile/<?php echo $guide['id']; ?>" class="btn btn-outline-primary w-100">View Profile</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i> No tour guides found matching your criteria. Try adjusting your filters.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 