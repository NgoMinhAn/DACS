<!-- Admin Guide Management -->
<div class="container py-4">
    <h2 class="mb-4 fw-bold"><i class="fas fa-user-tie me-2"></i>Guide Management</h2>
    <div class="mb-3">
        <a href="<?php echo url('admin/guideApplications'); ?>" class="btn btn-warning btn-lg">
            <i class="fas fa-user-clock"></i> Pending Guide Applications
        </a>
    </div>
    <?php if (!empty($guides)): ?>
        <div class="table-responsive shadow rounded-4">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-success">
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
                            <td>
                                <?php if ($guide->verified): ?>
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Yes</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">No</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo is_null($guide->avg_rating) ? '-' : number_format($guide->avg_rating, 2); ?></td>
                            <td><?php echo $guide->total_reviews ?? 0; ?></td>
                            <td>
                                <a href="<?php echo url('admin/editGuide/' . $guide->id); ?>" class="btn btn-info btn-sm me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="post" action="<?php echo url('admin/deleteGuide/' . $guide->id); ?>" class="d-inline">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this guide? This cannot be undone.');">
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
            <i class="fas fa-info-circle me-2"></i>No guides found.
        </div>
    <?php endif; ?>
</div>