<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="card">
                <div class="card-body text-center">
                    <img src="<?= isset($user) && !empty($user->profile_image) ? $user->profile_image : 'https://via.placeholder.com/150' ?>" 
                         class="img-fluid rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;" 
                         alt="<?= isset($user) ? htmlspecialchars($user->name) : 'User' ?>">
                    
                    <h5 class="mb-1"><?= isset($user) ? htmlspecialchars($user->name) : 'User Name' ?></h5>
                    <p class="text-muted mb-3">
                        <?php if (isset($user) && $user->is_guide): ?>
                            <span class="badge badge-info">Tour Guide</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Tourist</span>
                        <?php endif; ?>
                    </p>
                    
                    <div class="list-group list-group-flush">
                        <a href="/users/profile" class="list-group-item list-group-item-action active">
                            <i class="fas fa-user mr-2"></i> My Profile
                        </a>
                        <a href="/bookings" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-check mr-2"></i> My Bookings
                        </a>
                        <a href="/reviews/my" class="list-group-item list-group-item-action">
                            <i class="fas fa-star mr-2"></i> My Reviews
                        </a>
                        <?php if (isset($user) && $user->is_guide): ?>
                            <a href="/guides/dashboard" class="list-group-item list-group-item-action">
                                <i class="fas fa-compass mr-2"></i> Guide Dashboard
                            </a>
                        <?php endif; ?>
                        <a href="/users/settings" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog mr-2"></i> Account Settings
                        </a>
                        <a href="/users/logout" class="list-group-item list-group-item-action text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Content -->
        <div class="col-lg-9">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">My Profile</h4>
                    <a href="/users/edit" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit mr-1"></i> Edit Profile
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0 font-weight-bold">Full Name</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"><?= isset($user) ? htmlspecialchars($user->name) : 'User Name' ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0 font-weight-bold">Email</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"><?= isset($user) ? htmlspecialchars($user->email) : 'user@example.com' ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0 font-weight-bold">Phone</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"><?= (isset($user) && !empty($user->phone)) ? htmlspecialchars($user->phone) : 'Not provided' ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0 font-weight-bold">Location</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"><?= (isset($user) && !empty($user->location)) ? htmlspecialchars($user->location) : 'Not provided' ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0 font-weight-bold">Bio</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">
                                <?= (isset($user) && !empty($user->bio)) ? nl2br(htmlspecialchars($user->bio)) : 'No bio provided' ?>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0 font-weight-bold">Member Since</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">
                                <?= isset($user) ? date('F j, Y', strtotime($user->created_at)) : date('F j, Y') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Bookings -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Bookings</h5>
                    <a href="/bookings" class="btn btn-sm btn-link">View All</a>
                </div>
                <div class="card-body">
                    <?php if (isset($recent_bookings) && !empty($recent_bookings)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Guide</th>
                                        <th>Tour Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_bookings as $booking): ?>
                                        <tr>
                                            <td><?= date('M d, Y', strtotime($booking->date)) ?></td>
                                            <td>
                                                <a href="/guides/<?= $booking->guide_id ?>">
                                                    <?= htmlspecialchars($booking->guide_name) ?>
                                                </a>
                                            </td>
                                            <td><?= htmlspecialchars($booking->tour_type) ?></td>
                                            <td>
                                                <?php if ($booking->status === 'confirmed'): ?>
                                                    <span class="badge badge-success">Confirmed</span>
                                                <?php elseif ($booking->status === 'pending'): ?>
                                                    <span class="badge badge-warning">Pending</span>
                                                <?php elseif ($booking->status === 'cancelled'): ?>
                                                    <span class="badge badge-danger">Cancelled</span>
                                                <?php elseif ($booking->status === 'completed'): ?>
                                                    <span class="badge badge-info">Completed</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="/bookings/<?= $booking->id ?>" class="btn btn-sm btn-outline-secondary">Details</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p>You don't have any bookings yet.</p>
                            <a href="/guides" class="btn btn-primary">Find a Tour Guide</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Recent Reviews -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Reviews</h5>
                    <a href="/reviews/my" class="btn btn-sm btn-link">View All</a>
                </div>
                <div class="card-body">
                    <?php if (isset($recent_reviews) && !empty($recent_reviews)): ?>
                        <?php foreach ($recent_reviews as $review): ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <a href="/guides/<?= $review->guide_id ?>" class="font-weight-bold">
                                            <?= htmlspecialchars($review->guide_name) ?>
                                        </a>
                                        <small class="text-muted ml-2">
                                            <?= date('M d, Y', strtotime($review->created_at)) ?>
                                        </small>
                                    </div>
                                    <div class="rating small">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $review->rating): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="mb-0"><?= htmlspecialchars($review->comment) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-star-half-alt fa-3x text-muted mb-3"></i>
                            <p>You haven't left any reviews yet.</p>
                            <p class="text-muted">After completing a tour, you can share your experience.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>