<!-- Admin Edit Category -->
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow rounded-4">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Category</h3>
                </div>
                <div class="card-body">
                    <?php if (!$category): ?>
                        <div class="alert alert-danger">
                            Category not found.
                        </div>
                        <a href="<?php echo url('admin/categories'); ?>" class="btn btn-secondary">Back to Categories</a>
                    <?php else: ?>
                        <form method="POST" action="<?php echo url('admin/editCategory/' . $category->id); ?>" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required 
                                       placeholder="e.g., Adventure, Food, Cultural" value="<?php echo htmlspecialchars($category->name); ?>">
                                <small class="form-text text-muted">This will be the display name for the category.</small>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" 
                                          placeholder="Describe what this category is about..."><?php echo htmlspecialchars($category->description ?? ''); ?></textarea>
                                <small class="form-text text-muted">Optional description for the category.</small>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label fw-bold">Category Image <span class="text-info">(Card & Banner)</span></label>
                                <?php if ($category->image): ?>
                                    <div class="mb-2">
                                        <p class="text-muted mb-1">Current Image:</p>
                                        <img src="<?php echo url('public/img/categories/' . htmlspecialchars($category->image)); ?>" 
                                             alt="Current category image" 
                                             style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #ddd;">
                                        <p class="text-info mt-1 mb-0"><small><i class="fas fa-info-circle"></i> This image is used for both card and banner</small></p>
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <small class="form-text text-muted">
                                    Upload a new image to replace the current one (JPG, JPEG, PNG, GIF - Max: 5MB). 
                                    <strong class="text-info">This image will be used for both the category card and banner.</strong>
                                </small>
                                <div id="imagePreview" class="mt-2" style="display: none;">
                                    <p class="text-muted mb-1">New Image Preview:</p>
                                    <img id="previewImg" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #ddd;">
                                    <p class="text-info mt-2 mb-0"><small><i class="fas fa-info-circle"></i> This image will be used for both card and banner</small></p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="alert alert-info">
                                    <strong>Info:</strong> This category is being used by <strong><?php echo $category->guide_count ?? 0; ?></strong> guide(s).
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?php echo url('admin/categories'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Category
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
});

</script>

