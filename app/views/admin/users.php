<!-- Admin User Management -->
<div class="container py-4">
    <h2 class="mb-4 fw-bold"><i class="fas fa-users me-2"></i>User Management</h2>
    <?php if (!empty($users)): ?>
        <div class="table-responsive shadow rounded-4">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user->id); ?></td>
                            <td><?php echo htmlspecialchars($user->name); ?></td>
                            <td><?php echo htmlspecialchars($user->email); ?></td>
                            <td>
                                <a href="<?php echo url('admin/editUser/' . $user->id); ?>" class="btn btn-info btn-sm me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="post" action="<?php echo url('admin/deleteUser/' . $user->id); ?>" class="d-inline">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user? This cannot be undone.');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info shadow rounded-4 mt-4 text-center fs-5">
            <i class="fas fa-info-circle me-2"></i>No users found.
        </div>
    <?php endif; ?>
</div>