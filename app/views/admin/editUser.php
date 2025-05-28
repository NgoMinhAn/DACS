<div class="container py-4">
    <div class="card shadow rounded-4 mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h2 class="mb-4 fw-bold"><i class="fas fa-user-edit me-2"></i>Edit User</h2>
            <form method="post">
                <div class="mb-3">
                    <label class="fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user->name); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user->email); ?>" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Changes
                    </button>
                    <a href="<?php echo url('admin/users'); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>