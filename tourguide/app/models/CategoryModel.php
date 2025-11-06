<?php
/**
 * Category Model
 * Handles all database operations related to tour categories/specialties
 */
class CategoryModel
{
    private $db;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get all categories
     * 
     * @return array
     */
    public function getAllCategories()
    {
        $this->db->query("SELECT * FROM specialties ORDER BY name ASC");
        return $this->db->resultSet();
    }

    /**
     * Get category by ID
     * 
     * @param int $id
     * @return object|null
     */
    public function getCategoryById($id)
    {
        $this->db->query("SELECT * FROM specialties WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Get category by name
     * 
     * @param string $name
     * @return object|null
     */
    public function getCategoryByName($name)
    {
        $this->db->query("SELECT * FROM specialties WHERE name = :name");
        $this->db->bind(':name', $name);
        return $this->db->single();
    }

    /**
     * Create a new category
     * 
     * @param array $data
     * @return bool
     */
    public function createCategory($data)
    {
        $this->db->query("INSERT INTO specialties (name, description, icon, image) VALUES (:name, :description, :icon, :image)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description'] ?? null);
        $this->db->bind(':icon', $data['icon'] ?? null);
        $this->db->bind(':image', $data['image'] ?? null);
        
        if ($this->db->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Update a category
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateCategory($id, $data)
    {
        // Build query dynamically to handle optional image update
        $updates = ['name', 'description', 'icon'];
        $params = [];
        
        if (isset($data['image'])) {
            $updates[] = 'image';
            $params['image'] = $data['image'];
        }
        
        $setClause = [];
        foreach ($updates as $field) {
            $setClause[] = $field . ' = :' . $field;
        }
        
        $this->db->query("UPDATE specialties SET " . implode(', ', $setClause) . " WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description'] ?? null);
        $this->db->bind(':icon', $data['icon'] ?? null);
        
        if (isset($params['image'])) {
            $this->db->bind(':image', $params['image']);
        }
        
        if ($this->db->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Delete a category
     * 
     * @param int $id
     * @return bool
     */
    public function deleteCategory($id)
    {
        // Check if category is being used by any guides
        $this->db->query("SELECT COUNT(*) as count FROM guide_specialties WHERE specialty_id = :id");
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        
        if ($result && $result->count > 0) {
            return false; // Cannot delete category that is in use
        }
        
        // Get category info before deleting to remove files
        $category = $this->getCategoryById($id);
        
        $this->db->query("DELETE FROM specialties WHERE id = :id");
        $this->db->bind(':id', $id);
        
        if ($this->db->execute()) {
            // Delete associated image file if it exists
            // Note: banner_image and image now use the same file
            if ($category && $category->image) {
                $basePath = dirname(__DIR__, 2);
                $imagePath = $basePath . '/public/img/categories/' . $category->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Get count of guides using this category
     * 
     * @param int $id
     * @return int
     */
    public function getGuideCountByCategory($id)
    {
        $this->db->query("SELECT COUNT(*) as count FROM guide_specialties WHERE specialty_id = :id");
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }
}

