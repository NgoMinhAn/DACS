<div class="container py-4">
<?php if (isset($_SESSION['admin_message'])): ?>
    <div class="mt-2">
        <?php echo $_SESSION['admin_message']; unset($_SESSION['admin_message']); ?>
    </div>
<?php endif; ?>
    <h2 class="mb-4 fw-bold"><i class="fas fa-user-clock me-2"></i>Pending Guide Applications</h2>
    <?php if (!empty($applications)): ?>
        <div class="table-responsive shadow rounded-4">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-warning">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Location</th>
                        <th>Phone</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($app->id); ?></td>
                            <td><?php echo htmlspecialchars($app->name); ?></td>
                            <td><?php echo htmlspecialchars($app->email); ?></td>
                            <td><?php echo htmlspecialchars($app->location); ?></td>
                            <td><?php echo htmlspecialchars($app->phone); ?></td>
                            <td><?php echo htmlspecialchars($app->created_at); ?></td>
                            <td>
                                <a href="<?php echo url('admin/guideApplication/' . $app->id); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info shadow rounded-4 mt-4 text-center fs-5">
            <i class="fas fa-info-circle me-2"></i>No pending guide applications found.
        </div>
    <?php endif; ?>
</div> 