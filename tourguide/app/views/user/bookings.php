<!-- My Bookings Page -->
<div class="container my-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-12 scroll-animate fade-up">
            <h1 class="display-5 fw-bold mb-3">
                <i class="fas fa-calendar-check me-2" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;"></i>
                My Bookings
            </h1>
            <p class="lead text-muted">Manage and view all your tour guide bookings</p>
        </div>
    </div>

    <?php if (empty($bookings)): ?>
        <!-- Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                        </div>
                        <h3 class="fw-bold mb-3">No Bookings Yet</h3>
                        <p class="text-muted mb-4">You haven't booked any tours yet. Start exploring and find your perfect guide!</p>
                        <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-lg rounded-pill px-5 shadow text-white" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                            <i class="fas fa-search me-2"></i>Browse Guides
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Bookings Grid -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($bookings as $index => $booking): ?>
                <div class="col scroll-animate fade-up <?php echo 'delay-' . min(($index % 5) + 1, 5); ?>">
                    <div class="card h-100 border-0 shadow-lg rounded-4 guide-card">
                        <!-- Status Badge -->
                        <div class="position-absolute top-0 end-0 p-3" style="z-index: 2;">
                            <?php
                                $statusClass = '';
                                $statusIcon = '';
                                switch ($booking->status) {
                                    case 'pending':
                                        $statusClass = 'bg-warning text-dark';
                                        $statusIcon = 'fa-clock';
                                        break;
                                    case 'confirmed':
                                        $statusClass = 'bg-success';
                                        $statusIcon = 'fa-check-circle';
                                        break;
                                    case 'completed':
                                        $statusClass = 'bg-primary';
                                        $statusIcon = 'fa-check-double';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-danger';
                                        $statusIcon = 'fa-times-circle';
                                        break;
                                    default:
                                        $statusClass = 'bg-secondary';
                                        $statusIcon = 'fa-info-circle';
                                }
                            ?>
                            <span class="badge rounded-pill px-3 py-2 <?php echo $statusClass; ?>">
                                <i class="fas <?php echo $statusIcon; ?> me-1"></i>
                                <?php echo ucfirst($booking->status); ?>
                            </span>
                        </div>

                        <!-- Guide Image -->
                        <div class="card-img-wrapper position-relative overflow-hidden">
                            <img src="<?php echo url('assets/images/profiles/' . ($booking->guide_image ?? 'default.jpg')); ?>" 
                                 class="card-img-top guide-card-img" 
                                 alt="<?php echo htmlspecialchars($booking->guide_name); ?>" 
                                 style="height: 200px; object-fit: cover;">
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <h5 class="card-title mb-3 text-dark fw-bold">
                                <i class="fas fa-user-tie me-2 text-primary"></i>
                                <?php echo htmlspecialchars($booking->guide_name); ?>
                            </h5>

                            <!-- Booking Details -->
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-alt text-primary me-2" style="width: 20px;"></i>
                                    <span class="text-dark">
                                        <strong>Date:</strong> <?php echo date('d/m/Y', strtotime($booking->booking_date)); ?>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock text-primary me-2" style="width: 20px;"></i>
                                    <span class="text-dark">
                                        <strong>Time:</strong> <?php echo htmlspecialchars($booking->start_time . ' - ' . $booking->end_time); ?>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2" style="width: 20px;"></i>
                                    <span class="text-dark">
                                        <strong>Meeting:</strong> <?php echo htmlspecialchars($booking->meeting_location); ?>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users text-primary me-2" style="width: 20px;"></i>
                                    <span class="text-dark">
                                        <strong>People:</strong> <?php echo (int)$booking->number_of_people; ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="card-footer bg-white border-0 pt-0 pb-3">
                            <div class="d-grid gap-2">
                                <a href="<?php echo url('user/chat/' . $booking->id); ?>" class="btn rounded-pill px-4 text-white" style="background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);">
                                    <i class="fas fa-comments me-2"></i>Chat with Guide
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.guide-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.guide-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}

.card-img-wrapper {
    border-radius: 1rem 1rem 0 0;
}

.guide-card-img {
    transition: transform 0.3s ease;
}

.guide-card:hover .guide-card-img {
    transform: scale(1.05);
}
</style>
