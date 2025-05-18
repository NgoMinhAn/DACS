<!-- Admin Dashboard -->
<div class="container py-4">
    <h2>Admin Dashboard</h2>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text fs-3"><?php echo isset($userCount) ? $userCount : '-'; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Guides</h5>
                    <p class="card-text fs-3"><?php echo isset($guideCount) ? $guideCount : '-'; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- User Management -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            User Management
        </div>
        <div class="card-body">
            <a href="<?php echo url('admin/users'); ?>" class="btn btn-outline-primary">View All Users</a>
            <a href="<?php echo url('admin/addUser'); ?>" class="btn btn-outline-success">Add New User</a>
        </div>
    </div>

    <!-- Guide Management -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            Guide Management
        </div>
        <div class="card-body">
            <a href="<?php echo url('admin/guides'); ?>" class="btn btn-outline-secondary">View All Guides</a>
            <a href="<?php echo url('admin/addGuide'); ?>" class="btn btn-outline-success">Add New Guide</a>
        </div>
    </div>
</div>