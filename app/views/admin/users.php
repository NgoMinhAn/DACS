<!-- Admin User Management -->
<div class="container py-4">
    <h2>User Management</h2>
    <?php if (!empty($users)): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
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
                            <!-- Edit Button -->
                            <a href="<?php echo url('admin/editUser/' . $user->id); ?>" class="btn btn-info btn-sm">Edit</a>
                            <!-- Delete Button -->
                            <form method="post" action="<?php echo url('admin/deleteUser/' . $user->id); ?>" class="d-inline">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user? This cannot be undone.');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No users found.</div>
    <?php endif; ?>
</div>