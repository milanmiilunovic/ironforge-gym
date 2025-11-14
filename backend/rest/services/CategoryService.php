<?php
require_once __DIR__ . '/../dao/CategoryDao.php';


class CategoryService {
    private $categoryDao;

    public function __construct() {
        $this->categoryDao = new CategoryDao();
    }

    public function getAllCategories() {
        return $this->categoryDao->getAll();
    }

    public function getCategoryById($id) {
        $category = $this->categoryDao->getById($id);
        if (!$category) throw new Exception("Category not found");
        return $category;
    }

    public function createCategory($data) {
        if (empty($data['name'])) {
            throw new Exception("Category name is required");
        }

        $result = $this->categoryDao->insert($data);
        if (!$result) throw new Exception("Failed to create category");

        return ['success' => true, 'message' => 'Category created successfully'];
    }

    public function updateCategory($id, $data) {
        $existing = $this->categoryDao->getById($id);
        if (!$existing) throw new Exception("Category not found");

        $result = $this->categoryDao->update($id, $data);
        if (!$result) throw new Exception("Failed to update category");

        return ['success' => true, 'message' => 'Category updated successfully'];
    }

    public function deleteCategory($id) {
        $existing = $this->categoryDao->getById($id);
        if (!$existing) throw new Exception("Category not found");

        $result = $this->categoryDao->delete($id);
        if (!$result) throw new Exception("Failed to delete category");

        return ['success' => true, 'message' => 'Category deleted successfully'];
    }
}
?>
