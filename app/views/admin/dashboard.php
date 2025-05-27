<!-- Admin Dashboard -->
<div class="container py-4">
    <h2 class="mb-4 fw-bold"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</h2>
    <div class="row mb-4 g-4">
        <div class="col-md-6">
            <div class="card text-white bg-info shadow rounded-4 h-100">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text fs-2 fw-bold"><?php echo isset($userCount) ? $userCount : '-'; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-success shadow rounded-4 h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-tie fa-2x mb-2"></i>
                    <h5 class="card-title">Total Guides</h5>
                    <p class="card-text fs-2 fw-bold"><?php echo isset($guideCount) ? $guideCount : '-'; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- User Management -->
    <div class="card mb-4 shadow rounded-4">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="fas fa-users-cog me-2"></i>User Management
        </div>
        <div class="card-body">
            <a href="<?php echo url('admin/users'); ?>" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-users"></i> View All Users
            </a>
        </div>
    </div>

    <!-- Guide Management -->
    <div class="card mb-4 shadow rounded-4">
        <div class="card-header bg-secondary text-white fw-bold">
            <i class="fas fa-user-tie me-2"></i>Guide Management
        </div>
        <div class="card-body">
            <a href="<?php echo url('admin/guides'); ?>" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-user-tie"></i> View All Guides
            </a>
        </div>
    </div>
</div>