<!-- Admin Guide Management -->
<div class="container py-4">
    <h2>Guide Management</h2>
    <div class="mb-3">
        <a href="<?php echo url('admin/guideApplications'); ?>" class="btn btn-warning">Pending Guide Applications</a>
    </div>
    <?php if (!empty($guides)): ?>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Verified</th>
                    <th>Avg. Rating</th>
                    <th>Total Reviews</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($guides as $guide): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($guide->id); ?></td>
                        <td><?php echo htmlspecialchars($guide->name); ?></td>
                        <td><?php echo htmlspecialchars($guide->email); ?></td>
                        <td><?php echo $guide->verified ? 'Yes' : 'No'; ?></td>
                        <td><?php echo is_null($guide->avg_rating) ? '-' : number_format($guide->avg_rating, 2); ?></td>
                        <td><?php echo $guide->total_reviews ?? 0; ?></td>
                        <td>
                            <a href="<?php echo url('admin/editGuide/' . $guide->id); ?>" class="btn btn-info btn-sm">Edit</a>
                            <form method="post" action="<?php echo url('admin/deleteGuide/' . $guide->id); ?>" class="d-inline">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this guide? This cannot be undone.');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No guides found.</div>
    <?php endif; ?>
</div>