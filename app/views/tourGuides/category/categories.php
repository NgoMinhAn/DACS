<!-- Categories Header -->
<div class="bg-primary text-white py-4 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="fw-bold">Tour Guide Categories</h1>
                <p class="lead">Browse our guides by their areas of expertise</p>
            </div>
        </div>
    </div>
</div>

<!-- All Categories -->
<section class="mb-5">
    <div class="container">
        <div class="row g-4">
            <!-- Guide Categories from Database -->
            <?php if(!empty($guide_categories)): ?>
                <?php foreach($guide_categories as $category): ?>
                    <div class="col-md-6 col-lg-3 mb-4">
                        <a href="<?php echo url('tourGuide/category/' . strtolower(str_replace(' & ', '-', str_replace(' ', '-', $category->name)))); ?>" class="text-decoration-none">
                            <div class="card h-100 shadow-sm category-card">
                                <!-- Always use category-default.jpg as the fallback image -->
                                <img src="<?php echo url('public/img/category-default.jpg'); ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($category->name); ?> Tours"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo htmlspecialchars($category->name); ?></h5>
                                    <p class="card-text text-muted"><?php echo htmlspecialchars($category->description ?? 'Explore with our experienced guides'); ?></p>
                                    <span class="btn btn-sm btn-outline-primary">View Guides</span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback Categories if None in Database -->
                <div class="col-md-6 col-lg-3 mb-4">
                    <a href="<?php echo url('tourGuide/category/city'); ?>" class="text-decoration-none">
                        <div class="card h-100 shadow-sm category-card">
                            <img src="<?php echo url('public/img/category-default.jpg'); ?>" class="card-img-top" alt="City Tours" style="height: 200px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title">City Guides</h5>
                                <p class="card-text text-muted">Explore urban landscapes with expert city guides</p>
                                <span class="btn btn-sm btn-outline-primary">View Guides</span>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4">
                    <a href="<?php echo url('tourGuide/category/adventure'); ?>" class="text-decoration-none">
                        <div class="card h-100 shadow-sm category-card">
                            <img src="<?php echo url('public/img/category-default.jpg'); ?>" class="card-img-top" alt="Adventure Tours" style="height: 200px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title">Adventure Guides</h5>
                                <p class="card-text text-muted">Thrilling experiences with adventure specialists</p>
                                <span class="btn btn-sm btn-outline-primary">View Guides</span>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4">
                    <a href="<?php echo url('tourGuide/category/cultural'); ?>" class="text-decoration-none">
                        <div class="card h-100 shadow-sm category-card">
                            <img src="<?php echo url('public/img/category-cultural.jpg'); ?>" class="card-img-top" alt="Cultural Tours" style="height: 200px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title">Cultural Guides</h5>
                                <p class="card-text text-muted">Immerse yourself in local culture and history</p>
                                <span class="btn btn-sm btn-outline-primary">View Guides</span>
                            </div>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-3 mb-4">
                    <a href="<?php echo url('tourGuide/category/food'); ?>" class="text-decoration-none">
                        <div class="card h-100 shadow-sm category-card">
                            <img src="<?php echo url('public/img/category-food.jpg'); ?>" class="card-img-top" alt="Food Tours" style="height: 200px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title">Food & Cuisine Guides</h5>
                                <p class="card-text text-muted">Taste local flavors with culinary experts</p>
                                <span class="btn btn-sm btn-outline-primary">View Guides</span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bg-light p-4 rounded-3 text-center">
                    <h3 class="fw-bold">Not finding what you're looking for?</h3>
                    <p class="mb-3">Try our advanced search to find guides with specific expertise</p>
                    <a href="<?php echo url('tourGuide/search'); ?>" class="btn btn-primary">Advanced Search</a>
                </div>
            </div>
        </div>
    </div>
</section>
