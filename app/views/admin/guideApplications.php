<div class="container py-4">
<?php if (isset($_SESSION['admin_message'])): ?>
    <div class="mt-2">
        <?php echo $_SESSION['admin_message']; unset($_SESSION['admin_message']); ?>
    </div>
<?php endif; ?>
    <h2>Pending Guide Applications</h2>
    <?php if (!empty($applications)): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
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
                            <a href="<?php echo url('admin/guideApplication/' . $app->id); ?>" class="btn btn-info btn-sm">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No pending guide applications found.</div>
    <?php endif; ?>
</div> 