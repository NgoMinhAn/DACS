<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 fw-bold">
                <i class="fas fa-file-alt me-2 text-primary"></i>Guide Application Detail
            </h2>
            <p class="text-muted mb-0">Review and process guide application</p>
        </div>
        <a href="<?php echo url('admin/guideApplications'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <!-- Applicant Info Card -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-circle fa-2x me-3"></i>
                <div>
                    <h5 class="mb-0 fw-bold">Applicant Information</h5>
                    <small class="opacity-75"><?php echo htmlspecialchars($application->name); ?> (<?php echo htmlspecialchars($application->email); ?>)</small>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded" style="background-color: #f8f9fa;">
                                <i class="fas fa-map-marker-alt text-primary me-3 fa-lg"></i>
                                <div>
                                    <small class="text-muted d-block">Location</small>
                                    <strong><?php echo htmlspecialchars($application->location ?? 'N/A'); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded" style="background-color: #f8f9fa;">
                                <i class="fas fa-phone text-primary me-3 fa-lg"></i>
                                <div>
                                    <small class="text-muted d-block">Phone</small>
                                    <strong><?php echo htmlspecialchars($application->phone ?? 'N/A'); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded" style="background-color: #f8f9fa;">
                                <i class="fas fa-calendar-alt text-primary me-3 fa-lg"></i>
                                <div>
                                    <small class="text-muted d-block">Submitted At</small>
                                    <strong><?php echo date('d/m/Y H:i', strtotime($application->created_at)); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 rounded" style="background-color: #f8f9fa;">
                                <i class="fas fa-info-circle text-primary me-3 fa-lg"></i>
                                <div>
                                    <small class="text-muted d-block">Status</small>
                                    <span class="badge bg-<?php echo $application->status == 'pending' ? 'warning' : ($application->status == 'approved' ? 'success' : 'danger'); ?> px-3 py-2">
                                        <?php echo ucfirst($application->status); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <?php if ($application->profile_image): ?>
                        <img src="<?php echo url('public/uploads/avatars/' . $application->profile_image); ?>" 
                             alt="Profile Image" 
                             class="img-thumbnail rounded-circle shadow-sm" 
                             style="max-width: 200px; height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 200px; height: 200px;">
                            <i class="fas fa-user fa-5x text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Guide Profile Details -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Bio Section -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-edit me-2 text-primary"></i>Bio
                    </h5>
                </div>
                <div class="card-body">
                    <div class="p-3 rounded" style="background-color: #f8f9fa; min-height: 80px;">
                        <?php echo nl2br(htmlspecialchars($application->bio ?? 'No bio provided')); ?>
                    </div>
                </div>
            </div>

            <!-- Experience Section -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-briefcase me-2 text-primary"></i>Experience
                    </h5>
                </div>
                <div class="card-body">
                    <div class="p-3 rounded" style="background-color: #f8f9fa; min-height: 80px;">
                        <?php echo nl2br(htmlspecialchars($application->experience ?? 'No experience provided')); ?>
                    </div>
                </div>
            </div>

            <!-- Certifications Section -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-certificate me-2 text-primary"></i>Certifications
                    </h5>
                </div>
                <div class="card-body">
                    <div class="p-3 rounded" style="background-color: #f8f9fa; min-height: 80px;">
                        <?php echo nl2br(htmlspecialchars($application->certifications ?? 'No certifications provided')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Pricing Card -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-dollar-sign me-2"></i>Pricing
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center p-3 mb-2 rounded" style="background-color: #f8f9fa;">
                        <div>
                            <small class="text-muted d-block">Hourly Rate</small>
                            <h4 class="mb-0 fw-bold text-success">$<?php echo number_format($application->hourly_rate ?? 0, 2); ?></h4>
                        </div>
                        <i class="fas fa-clock fa-2x text-success"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background-color: #f8f9fa;">
                        <div>
                            <small class="text-muted d-block">Daily Rate</small>
                            <h4 class="mb-0 fw-bold text-success">$<?php echo number_format($application->daily_rate ?? 0, 2); ?></h4>
                        </div>
                        <i class="fas fa-calendar-day fa-2x text-success"></i>
                    </div>
                </div>
            </div>

            <!-- Specialties & Languages Card -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-tags me-2"></i>Specialties & Languages
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">
                            <i class="fas fa-star me-1"></i><strong>Specialties:</strong>
                        </small>
                        <?php if (!empty($application->specialty)): ?>
                            <?php 
                                $specialties = explode(',', $application->specialty);
                                foreach ($specialties as $spec): 
                            ?>
                                <span class="badge bg-primary me-1 mb-1 px-3 py-2"><?php echo htmlspecialchars(trim($spec)); ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="text-muted">No specialties specified</span>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <div>
                        <small class="text-muted d-block mb-2">
                            <i class="fas fa-language me-1"></i><strong>Languages:</strong>
                        </small>
                        <?php if (!empty($application->languages)): ?>
                            <?php 
                                $languages = explode(',', $application->languages);
                                foreach ($languages as $lang): 
                            ?>
                                <span class="badge bg-info me-1 mb-1 px-3 py-2"><?php echo htmlspecialchars(trim($lang)); ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="text-muted">No languages specified</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" class="d-flex gap-3 justify-content-end">
                <button type="submit" 
                        name="action" 
                        value="approve" 
                        class="btn btn-success btn-lg px-5"
                        onclick="return confirm('Are you sure you want to approve this application?');">
                    <i class="fas fa-check-circle me-2"></i>Approve Application
                </button>
                <button type="submit" 
                        name="action" 
                        value="reject" 
                        class="btn btn-danger btn-lg px-5"
                        onclick="return confirm('Are you sure you want to reject this application?');">
                    <i class="fas fa-times-circle me-2"></i>Reject Application
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.badge {
    font-size: 0.85rem;
    font-weight: 500;
}
</style>
