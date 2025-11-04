<?php
/**
 * Admin Controller
 * Handles all admin dashboard functions
 */
class AdminController
{
    private $userModel;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Check if user is logged in and is admin
        if (!isLoggedIn() || $_SESSION['user_type'] !== 'admin') {
            redirect('account/login');
        }

        // Load the user model
        $this->userModel = new UserModel();
    }

    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        // Get counts for dashboard
        $userCount = $this->userModel->getUserCount();
        $guideCount = $this->userModel->getGuideCount();

        $data = [
            'title' => 'Admin Dashboard',
            'userCount' => $userCount,
            'guideCount' => $guideCount
        ];

        $this->loadView('admin/dashboard', $data);
    }

    /**
     * List all users
     */
    public function users()
    {
        $userModel = new UserModel();
        $users = $userModel->getAllUsers();
        $this->loadView('admin/users', ['users' => $users]);
    }
    /**
     * List all guides
     */
    public function guides()
    {
        // Get all guides
        $guides = $this->userModel->getAllGuides();

        $data = [
            'title' => 'Manage Guides',
            'guides' => $guides
        ];

        $this->loadView('admin/guides', $data);
    }

    /**
     * Load a view with the header and footer
     * 
     * @param string $view The view to load
     * @param array $data The data to pass to the view
     */
    private function loadView($view, $data = [])
    {
        // Extract data variables into the current symbol table
        extract($data);

        // Load header
        require_once VIEW_PATH . '/shares/header.php';

        // Load the view - create a temporary fallback if view doesn't exist
        $viewPath = VIEW_PATH . '/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // Display a temporary view
            echo '<div class="container mt-4">';
            echo '<div class="card">';
            echo '<div class="card-header bg-primary text-white">';
            echo '<h2>' . $title . '</h2>';
            echo '</div>';
            echo '<div class="card-body">';

            if (isset($userCount) && isset($guideCount)) {
                echo '<div class="row">';
                echo '<div class="col-md-6">';
                echo '<div class="card bg-light mb-3">';
                echo '<div class="card-body text-center">';
                echo '<h5 class="card-title">Total Users</h5>';
                echo '<p class="display-4">' . $userCount . '</p>';
                echo '</div></div></div>';

                echo '<div class="col-md-6">';
                echo '<div class="card bg-light mb-3">';
                echo '<div class="card-body text-center">';
                echo '<h5 class="card-title">Total Guides</h5>';
                echo '<p class="display-4">' . $guideCount . '</p>';
                echo '</div></div></div>';
                echo '</div>';

                echo '<div class="alert alert-info">';
                echo 'Welcome to the admin dashboard. This is a temporary view until you create proper admin views.';
                echo '</div>';

                echo '<div class="list-group mt-4">';
                echo '<a href="' . url('admin/users') . '" class="list-group-item list-group-item-action">Manage Users</a>';
                echo '<a href="' . url('admin/guides') . '" class="list-group-item list-group-item-action">Manage Guides</a>';
                echo '</div>';
            } else if (isset($users)) {
                echo '<p>Here you can manage users. This is a placeholder for the users management page.</p>';
                echo '<a href="' . url('admin/dashboard') . '" class="btn btn-primary">Back to Dashboard</a>';
            } else if (isset($guides)) {
                echo '<p>Here you can manage guides. This is a placeholder for the guides management page.</p>';
                echo '<a href="' . url('admin/dashboard') . '" class="btn btn-primary">Back to Dashboard</a>';
            }

            echo '</div></div></div>';
        }

        // Load footer
        require_once VIEW_PATH . '/shares/footer.php';
    }

    public function editUser($id)
    {
        $userModel = new UserModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $userModel->updateUser($id, $name, $email);
            redirect('admin/users');
        }
        $user = $userModel->getUserById($id);
        $this->loadView('admin/editUser', ['user' => $user]);
    }

    public function deleteUser($id)
    {
        $userModel = new UserModel();
        $userModel->deleteUser($id);
        redirect('admin/users');
    }
    public function editGuide($id)
    {
        $guideModel = new GuideModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'status' => $_POST['status'] ?? 'inactive',
                'verified' => $_POST['verified'] ?? 0,
                'avg_rating' => $_POST['avg_rating'] ?? 0,
                'total_reviews' => $_POST['total_reviews'] ?? 0,
                'hourly_rate' => isset($_POST['hourly_rate']) && $_POST['hourly_rate'] !== '' ? $_POST['hourly_rate'] : 0,
                'daily_rate' => isset($_POST['daily_rate']) && $_POST['daily_rate'] !== '' ? $_POST['daily_rate'] : 0,
                'available' => isset($_POST['available']) ? $_POST['available'] : 0,
                'specialties' => $_POST['specialties'] ?? '',
                'languages' => $_POST['languages'] ?? '',
            ];
            $data['experience_years'] = $_POST['experience_years'] ?? 0;
            $data['fluent_languages'] = $_POST['fluent_languages'] ?? '';
            $guideModel->updateGuide($id, $data);
            redirect('admin/guides');
        }
        $guide = $guideModel->getGuideById($id);
        $this->loadView('admin/editGuide', ['guide' => $guide]);
    }

    /**
     * List all pending guide applications
     */
    public function guideApplications() {
        $db = new Database();
        $db->query('SELECT ga.*, u.name, u.email FROM guide_applications ga JOIN users u ON ga.user_id = u.id WHERE ga.status = "pending" ORDER BY ga.created_at DESC');
        $applications = $db->resultSet();
        $this->loadView('admin/guideApplications', ['applications' => $applications]);
    }

    /**
     * View and process a single guide application
     */
    public function guideApplicationDetail($id) {
        $db = new Database();
        $db->query('SELECT ga.*, u.name, u.email FROM guide_applications ga JOIN users u ON ga.user_id = u.id WHERE ga.id = :id');
        $db->bind(':id', $id);
        $application = $db->single();
        if (!$application) {
            flash('admin_message', 'Application not found.', 'alert alert-danger');
            redirect('admin/guideApplications');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'approve') {
                // Cập nhật user_type, tạo guide_profiles nếu chưa có, cập nhật application
                $userId = $application->user_id;
                $db->query('UPDATE users SET user_type = "guide" WHERE id = :id');
                $db->bind(':id', $userId);
                $db->execute();
                // Kiểm tra user đã có guide_profiles chưa
                $db->query('SELECT id FROM guide_profiles WHERE user_id = :user_id');
                $db->bind(':user_id', $userId);
                $existingProfile = $db->single();
                if ($existingProfile) {
                    // Đã có profile, chỉ lấy guideProfileId để insert specialties/languages
                    $guideProfileId = $existingProfile->id;
                    // (Có thể update lại thông tin profile nếu muốn)
                } else {
                    // Chưa có profile, insert mới
                    $db->query('INSERT INTO guide_profiles (user_id, bio, location, experience_years, hourly_rate, daily_rate, available, verified, certification_info, profile_image) VALUES (:user_id, :bio, :location, :experience_years, :hourly_rate, :daily_rate, 1, 1, :certifications, :profile_image)');
                    $db->bind(':user_id', $userId);
                    $db->bind(':bio', $application->bio);
                    $db->bind(':location', $application->location);
                    $db->bind(':experience_years', 0);
                    $db->bind(':hourly_rate', $application->hourly_rate);
                    $db->bind(':daily_rate', $application->daily_rate);
                    $db->bind(':certifications', $application->certifications);
                    $db->bind(':profile_image', $application->profile_image);
                    $db->execute();
                    $guideProfileId = $db->lastInsertId();
                }
                // Copy specialties
                $specialties = explode(',', $application->specialty);
                foreach ($specialties as $specialtyName) {
                    $db->query('SELECT id FROM specialties WHERE name = :name');
                    $db->bind(':name', trim($specialtyName));
                    $specialty = $db->single();
                    if ($specialty) {
                        $db->query('INSERT INTO guide_specialties (guide_id, specialty_id) VALUES (:guide_id, :specialty_id)');
                        $db->bind(':guide_id', $guideProfileId);
                        $db->bind(':specialty_id', $specialty->id);
                        $db->execute();
                    }
                }
                // Copy languages nếu có trường languages trong application
                if (property_exists($application, 'languages') && !empty($application->languages)) {
                    $languages = explode(',', $application->languages);
                    foreach ($languages as $languageName) {
                        $db->query('SELECT id FROM languages WHERE name = :name');
                        $db->bind(':name', trim($languageName));
                        $language = $db->single();
                        if ($language) {
                            $db->query('INSERT INTO guide_languages (guide_id, language_id, fluency_level) VALUES (:guide_id, :language_id, :fluency)');
                            $db->bind(':guide_id', $guideProfileId);
                            $db->bind(':language_id', $language->id);
                            $db->bind(':fluency', 'fluent'); // hoặc lấy đúng fluency nếu có
                            $db->execute();
                        }
                    }
                }
                // Cập nhật trạng thái đơn
                $db->query('UPDATE guide_applications SET status = "approved", reviewed_at = NOW(), reviewed_by = :admin_id WHERE id = :id');
                $db->bind(':admin_id', $_SESSION['user_id']);
                $db->bind(':id', $id);
                $db->execute();
                flash('admin_message', 'Application approved and user is now a guide.', 'alert alert-success');
            } elseif ($action === 'reject') {
                $db->query('UPDATE guide_applications SET status = "rejected", reviewed_at = NOW(), reviewed_by = :admin_id WHERE id = :id');
                $db->bind(':admin_id', $_SESSION['user_id']);
                $db->bind(':id', $id);
                $db->execute();
                flash('admin_message', 'Application rejected.', 'alert alert-warning');
            }
            redirect('admin/guideApplications');
        } else {
            $this->loadView('admin/guideApplicationDetail', ['application' => $application]);
        }
    }

    public function deleteGuide($id)
    {
        try {
            $guideModel = new GuideModel();
            $guide = $guideModel->getGuideById($id);
            
            if (!$guide) {
                flash('admin_message', 'Guide not found.', 'alert alert-danger');
                redirect('admin/guides');
            }

            // Start transaction
            $db = new Database();
            $db->beginTransaction();

            try {
                // Delete from guide_languages
                $db->query('DELETE FROM guide_languages WHERE guide_id = :id');
                $db->bind(':id', $id);
                $db->execute();

                // Delete from guide_specialties
                $db->query('DELETE FROM guide_specialties WHERE guide_id = :id');
                $db->bind(':id', $id);
                $db->execute();

                // Delete from guide_reviews
                $db->query('DELETE FROM guide_reviews WHERE guide_id = :id');
                $db->bind(':id', $id);
                $db->execute();

                // Delete from bookings
                $db->query('DELETE FROM bookings WHERE guide_id = :id');
                $db->bind(':id', $id);
                $db->execute();

                // Delete from guide_profiles
                $db->query('DELETE FROM guide_profiles WHERE id = :id');
                $db->bind(':id', $id);
                $db->execute();

                // Update user type back to regular user
                $db->query('UPDATE users SET user_type = "user" WHERE id = :user_id');
                $db->bind(':user_id', $guide->user_id);
                $db->execute();

                // Commit transaction
                $db->commit();

                flash('admin_message', 'Guide successfully deleted.', 'alert alert-success');
            } catch (Exception $e) {
                // Rollback on error
                $db->rollBack();
                error_log("Error deleting guide: " . $e->getMessage());
                flash('admin_message', 'Error deleting guide. Please try again.', 'alert alert-danger');
            }
        } catch (Exception $e) {
            error_log("Error in deleteGuide: " . $e->getMessage());
            flash('admin_message', 'Error deleting guide. Please try again.', 'alert alert-danger');
        }

        redirect('admin/guides');
    }

    /**
     * List all categories
     */
    public function categories()
    {
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAllCategories();
        
        // Get guide count for each category
        foreach ($categories as $category) {
            $category->guide_count = $categoryModel->getGuideCountByCategory($category->id);
        }
        
        $data = [
            'title' => 'Manage Categories',
            'categories' => $categories
        ];
        
        $this->loadView('admin/categories', $data);
    }

    /**
     * Add a new category
     */
    public function addCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryModel = new CategoryModel();
            
            // Validate input
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            
            // Check if name is provided
            if (empty($name)) {
                flash('admin_message', 'Category name is required.', 'alert alert-danger');
                redirect('admin/categories');
            }
            
            // Check if category name already exists
            $existing = $categoryModel->getCategoryByName($name);
            if ($existing) {
                flash('admin_message', 'Category with this name already exists.', 'alert alert-danger');
                redirect('admin/categories');
            }
            
            // Handle category image upload
            $imageFileName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__, 2) . '/public/img/categories/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (!in_array($fileExtension, $allowedTypes)) {
                    flash('admin_message', 'Only JPG, JPEG, PNG & GIF files are allowed.', 'alert alert-danger');
                    redirect('admin/addCategory');
                } elseif ($_FILES['image']['size'] > 5000000) { // 5MB
                    flash('admin_message', 'File size must not exceed 5MB.', 'alert alert-danger');
                    redirect('admin/addCategory');
                } else {
                    $imageFileName = 'category_' . uniqid() . '.' . $fileExtension;
                    $targetPath = $uploadDir . $imageFileName;
                    
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                        flash('admin_message', 'Error uploading image. Please try again.', 'alert alert-danger');
                        redirect('admin/addCategory');
                    }
                }
            }

            // Create category
            // Note: Banner automatically uses the same image as category image
            $data = [
                'name' => $name,
                'description' => $description ?: null,
                'icon' => null,
                'image' => $imageFileName
            ];
            
            if ($categoryModel->createCategory($data)) {
                flash('admin_message', 'Category created successfully.', 'alert alert-success');
            } else {
                flash('admin_message', 'Failed to create category. Please try again.', 'alert alert-danger');
            }
            
            redirect('admin/categories');
        }
        
        $data = [
            'title' => 'Add New Category'
        ];
        
        $this->loadView('admin/addCategory', $data);
    }

    /**
     * Edit a category
     */
    public function editCategory($id)
    {
        $categoryModel = new CategoryModel();
        $category = $categoryModel->getCategoryById($id);
        
        if (!$category) {
            flash('admin_message', 'Category not found.', 'alert alert-danger');
            redirect('admin/categories');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate input
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            
            // Check if name is provided
            if (empty($name)) {
                flash('admin_message', 'Category name is required.', 'alert alert-danger');
                redirect('admin/editCategory/' . $id);
            }
            
            // Check if category name already exists (excluding current category)
            $existing = $categoryModel->getCategoryByName($name);
            if ($existing && $existing->id != $id) {
                flash('admin_message', 'Category with this name already exists.', 'alert alert-danger');
                redirect('admin/editCategory/' . $id);
            }
            
            // Handle category image upload
            $imageFileName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(__DIR__, 2) . '/public/img/categories/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (!in_array($fileExtension, $allowedTypes)) {
                    flash('admin_message', 'Only JPG, JPEG, PNG & GIF files are allowed.', 'alert alert-danger');
                    redirect('admin/editCategory/' . $id);
                } elseif ($_FILES['image']['size'] > 5000000) { // 5MB
                    flash('admin_message', 'File size must not exceed 5MB.', 'alert alert-danger');
                    redirect('admin/editCategory/' . $id);
                } else {
                    $imageFileName = 'category_' . uniqid() . '.' . $fileExtension;
                    $targetPath = $uploadDir . $imageFileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                        // Delete old image if exists
                        if ($category->image && file_exists($uploadDir . $category->image)) {
                            unlink($uploadDir . $category->image);
                        }
                    } else {
                        flash('admin_message', 'Error uploading image. Please try again.', 'alert alert-danger');
                        redirect('admin/editCategory/' . $id);
                    }
                }
            }

            // Update category
            $data = [
                'name' => $name,
                'description' => $description ?: null,
                'icon' => null
            ];
            
            // Only add image to update if a new image was uploaded
            // Note: Banner automatically uses the same image as category image
            if ($imageFileName) {
                $data['image'] = $imageFileName;
            }
            
            if ($categoryModel->updateCategory($id, $data)) {
                flash('admin_message', 'Category updated successfully.', 'alert alert-success');
                redirect('admin/categories');
            } else {
                flash('admin_message', 'Failed to update category. Please try again.', 'alert alert-danger');
                redirect('admin/editCategory/' . $id);
            }
        }
        
        // Get guide count for this category
        $category->guide_count = $categoryModel->getGuideCountByCategory($category->id);
        
        $data = [
            'title' => 'Edit Category',
            'category' => $category
        ];
        
        $this->loadView('admin/editCategory', $data);
    }

    /**
     * Delete a category
     */
    public function deleteCategory($id)
    {
        $categoryModel = new CategoryModel();
        $category = $categoryModel->getCategoryById($id);
        
        if (!$category) {
            flash('admin_message', 'Category not found.', 'alert alert-danger');
            redirect('admin/categories');
        }
        
        // Try to delete
        if ($categoryModel->deleteCategory($id)) {
            flash('admin_message', 'Category deleted successfully.', 'alert alert-success');
        } else {
            $guideCount = $categoryModel->getGuideCountByCategory($id);
            if ($guideCount > 0) {
                flash('admin_message', 'Cannot delete category. It is being used by ' . $guideCount . ' guide(s).', 'alert alert-danger');
            } else {
                flash('admin_message', 'Failed to delete category. Please try again.', 'alert alert-danger');
            }
        }
        
        redirect('admin/categories');
    }
}