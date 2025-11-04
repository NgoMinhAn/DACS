<!-- Admin Category Management -->
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold"><i class="fas fa-tags me-2"></i>Category Management</h2>
        <a href="<?php echo url('admin/addCategory'); ?>" class="btn btn-primary btn-lg">
            <i class="fas fa-plus-circle"></i> Add New Category
        </a>
    </div>

    <?php if (isset($_SESSION['admin_message'])): ?>
        <div class="<?php echo $_SESSION['admin_message_class']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['admin_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php 
        unset($_SESSION['admin_message']);
        unset($_SESSION['admin_message_class']);
        ?>
    <?php endif; ?>

    <?php if (!empty($categories)): ?>
        <div class="table-responsive shadow rounded-4">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-success">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Guides Using</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($category->id); ?></td>
                            <td>
                                <?php if (!empty($category->image)): ?>
                                    <img src="<?php echo url('public/img/categories/' . htmlspecialchars($category->image)); ?>" 
                                         alt="<?php echo htmlspecialchars($category->name); ?>" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                                <?php else: ?>
                                    <span class="text-muted">No image</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($category->name); ?></strong></td>
                            <td><?php echo htmlspecialchars($category->description ?? '-'); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo $category->guide_count ?? 0; ?></span>
                            </td>
                            <td><?php echo $category->created_at ? date('Y-m-d', strtotime($category->created_at)) : '-'; ?></td>
                            <td>
                                <a href="<?php echo url('admin/editCategory/' . $category->id); ?>" class="btn btn-info btn-sm me-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="post" action="<?php echo url('admin/deleteCategory/' . $category->id); ?>" class="d-inline">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.');">
                                        <i class="fas fa-trash-alt"></i> Delete
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
            <i class="fas fa-info-circle me-2"></i>No categories found. <a href="<?php echo url('admin/addCategory'); ?>" class="alert-link">Add your first category</a>
        </div>
    <?php endif; ?>
</div>

