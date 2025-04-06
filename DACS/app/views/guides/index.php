<div class="bg-light py-4">
    <div class="container">
        <h1 class="mb-4">Find Your Perfect Tour Guide</h1>
        
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="/guides" method="GET" class="guide-search-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="speciality">Speciality</label>
                                <select class="form-control" id="speciality" name="speciality">
                                    <option value="">All Specialities</option>
                                    <option value="Historical" <?= isset($_GET['speciality']) && $_GET['speciality'] == 'Historical' ? 'selected' : '' ?>>Historical Tours</option>
                                    <option value="Food" <?= isset($_GET['speciality']) && $_GET['speciality'] == 'Food' ? 'selected' : '' ?>>Food Tours</option>
                                    <option value="Adventure" <?= isset($_GET['speciality']) && $_GET['speciality'] == 'Adventure' ? 'selected' : '' ?>>Adventure Tours</option>
                                    <option value="Cultural" <?= isset($_GET['speciality']) && $_GET['speciality'] == 'Cultural' ? 'selected' : '' ?>>Cultural Tours</option>
                                    <option value="Nature" <?= isset($_GET['speciality']) && $_GET['speciality'] == 'Nature' ? 'selected' : '' ?>>Nature Tours</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="language">Language</label>
                                <select class="form-control" id="language" name="language">
                                    <option value="">Any Language</option>
                                    <option value="English" <?= isset($_GET['language']) && $_GET['language'] == 'English' ? 'selected' : '' ?>>English</option>
                                    <option value="Spanish" <?= isset($_GET['language']) && $_GET['language'] == 'Spanish' ? 'selected' : '' ?>>Spanish</option>
                                    <option value="French" <?= isset($_GET['language']) && $_GET['language'] == 'French' ? 'selected' : '' ?>>French</option>
                                    <option value="German" <?= isset($_GET['language']) && $_GET['language'] == 'German' ? 'selected' : '' ?>>German</option>
                                    <option value="Chinese" <?= isset($_GET['language']) && $_GET['language'] == 'Chinese' ? 'selected' : '' ?>>Chinese</option>
                                    <option value="Japanese" <?= isset($_GET['language']) && $_GET['language'] == 'Japanese' ? 'selected' : '' ?>>Japanese</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date">Available Date</label>
                                <input type="date" class="form-control" id="date" name="date" value="<?= isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '' ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Search Guides</button>
                            <a href="/guides" class="btn btn-outline-secondary ml-2">Clear Filters</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="row">
            <!-- This section would be dynamically populated with guides from the database -->
            <?php if (isset($guides) && !empty($guides)): ?>
                <?php foreach ($guides as $guide): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card guide-card h-100">
                            <div class="card-img-top text-center bg-light p-3">
                                <img src="<?= !empty($guide->profile_image) ? $guide->profile_image : 'https://via.placeholder.com/150' ?>" class="profile-img" alt="<?= htmlspecialchars($guide->getUser()->name) ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($guide->getUser()->name) ?></h5>
                                <p class="card-text text-muted"><i class="fas fa-map-marker-alt mr-1"></i> <?= htmlspecialchars($guide->location ?? 'Location not specified') ?></p>
                                <p class="card-text"><strong>Speciality:</strong> <?= htmlspecialchars($guide->speciality) ?></p>
                                
                                <div class="languages mb-2">
                                    <strong>Languages:</strong>
                                    <?php if (is_array($guide->languages)): ?>
                                        <?php foreach ($guide->languages as $language): ?>
                                            <span class="badge badge-pill badge-light border"><?= htmlspecialchars($language) ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="rating mb-3">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= floor($guide->rating)): ?>
                                            <i class="fas fa-star"></i>
                                        <?php elseif ($i - 0.5 <= $guide->rating): ?>
                                            <i class="fas fa-star-half-alt"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span class="ml-1"><?= number_format($guide->rating, 1) ?> (<?= $guide->review_count ?> reviews)</span>
                                </div>
                                
                                <p class="card-text"><strong>Hourly Rate:</strong> $<?= number_format($guide->hourly_rate, 2) ?></p>
                                
                                <a href="/guides/<?= $guide->id ?>" class="btn btn-outline-primary btn-block">View Profile</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i> No tour guides found matching your criteria. Try adjusting your search filters.
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Placeholder content when no actual guides are available -->
        <?php if (!isset($guides) || empty($guides)): ?>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card guide-card h-100">
                        <div class="card-img-top text-center bg-light p-3">
                            <img src="https://via.placeholder.com/150" class="profile-img" alt="John Doe">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">John Doe</h5>
                            <p class="card-text text-muted"><i class="fas fa-map-marker-alt mr-1"></i> New York, USA</p>
                            <p class="card-text"><strong>Speciality:</strong> Historical Tours</p>
                            
                            <div class="languages mb-2">
                                <strong>Languages:</strong>
                                <span class="badge badge-pill badge-light border">English</span>
                                <span class="badge badge-pill badge-light border">Spanish</span>
                            </div>
                            
                            <div class="rating mb-3">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <span class="ml-1">4.5 (120 reviews)</span>
                            </div>
                            
                            <p class="card-text"><strong>Hourly Rate:</strong> $35.00</p>
                            
                            <a href="#" class="btn btn-outline-primary btn-block">View Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card guide-card h-100">
                        <div class="card-img-top text-center bg-light p-3">
                            <img src="https://via.placeholder.com/150" class="profile-img" alt="Jane Smith">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Jane Smith</h5>
                            <p class="card-text text-muted"><i class="fas fa-map-marker-alt mr-1"></i> Paris, France</p>
                            <p class="card-text"><strong>Speciality:</strong> Food Tours</p>
                            
                            <div class="languages mb-2">
                                <strong>Languages:</strong>
                                <span class="badge badge-pill badge-light border">English</span>
                                <span class="badge badge-pill badge-light border">French</span>
                            </div>
                            
                            <div class="rating mb-3">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="ml-1">5.0 (87 reviews)</span>
                            </div>
                            
                            <p class="card-text"><strong>Hourly Rate:</strong> $45.00</p>
                            
                            <a href="#" class="btn btn-outline-primary btn-block">View Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card guide-card h-100">
                        <div class="card-img-top text-center bg-light p-3">
                            <img src="https://via.placeholder.com/150" class="profile-img" alt="Michael Johnson">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Michael Johnson</h5>
                            <p class="card-text text-muted"><i class="fas fa-map-marker-alt mr-1"></i> Tokyo, Japan</p>
                            <p class="card-text"><strong>Speciality:</strong> Cultural Tours</p>
                            
                            <div class="languages mb-2">
                                <strong>Languages:</strong>
                                <span class="badge badge-pill badge-light border">English</span>
                                <span class="badge badge-pill badge-light border">Japanese</span>
                            </div>
                            
                            <div class="rating mb-3">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                                <span class="ml-1">4.0 (65 reviews)</span>
                            </div>
                            
                            <p class="card-text"><strong>Hourly Rate:</strong> $40.00</p>
                            
                            <a href="#" class="btn btn-outline-primary btn-block">View Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Pagination -->
        <nav aria-label="Guide search results navigation">
            <ul class="pagination justify-content-center mt-4">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
                <li class="page-item active" aria-current="page">
                    <a class="page-link" href="#">1 <span class="sr-only">(current)</span></a>
                </li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div> 