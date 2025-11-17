<?php
require_once __DIR__ . '/BaseDao.php';


class CategoryDao extends BaseDao {
   public function __construct() {
       parent::__construct("categories");
   }

   public function getByName($name) {
       $stmt = $this->connection->prepare("SELECT * FROM categories WHERE name LIKE :name");
       $searchName = "%$name%";
       $stmt->bindParam(':name', $searchName);
       $stmt->execute();
       return $stmt->fetchAll();
   }

   public function getCategoryWithClasses($categoryId) {
       $stmt = $this->connection->prepare("
           SELECT c.*, COUNT(cl.class_id) as total_classes 
           FROM categories c 
           LEFT JOIN classes cl ON c.category_id = cl.category_id 
           WHERE c.category_id = :category_id 
           GROUP BY c.category_id
       ");
       $stmt->bindParam(':category_id', $categoryId);
       $stmt->execute();
       return $stmt->fetch();
   }
}
?>