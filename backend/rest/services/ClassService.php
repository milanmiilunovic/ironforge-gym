<?php
require_once __DIR__ . '/../dao/ClassDao.php';
require_once __DIR__ . '/../dao/CategoryDao.php';
require_once __DIR__ . '/../dao/TrainerDao.php';

class ClassService {
    private $classDao;
    private $categoryDao;
    private $trainerDao;

    public function __construct() {
        $this->classDao = new ClassDao();
        $this->categoryDao = new CategoryDao();
        $this->trainerDao = new TrainerDao();
    }

    public function getAllClasses() {
        return $this->classDao->getAll();
    }

    public function getClassById($id) {
        $class = $this->classDao->getById($id);
        if (!$class) throw new Exception("Class not found");
        return $class;
    }

    public function createClass($data) {
        if (empty($data['title']) || empty($data['category_id']) || empty($data['trainer_id']) || empty($data['schedule_time']) || empty($data['duration_minutes']) || empty($data['capacity'])) {
    throw new Exception("Missing required fields");
    }


        $category = $this->categoryDao->getById($data['category_id']);
        if (!$category) throw new Exception("Invalid category");

        $trainer = $this->trainerDao->getById($data['trainer_id']);
        if (!$trainer) throw new Exception("Invalid trainer");

        $result = $this->classDao->insert($data);
        if (!$result) throw new Exception("Failed to create class");

        return ['success' => true, 'message' => 'Class created successfully'];
    }

    public function updateClass($id, $data) {
        $existing = $this->classDao->getById($id);
        if (!$existing) throw new Exception("Class not found");

        $result = $this->classDao->update($id, $data);
        if (!$result) throw new Exception("Failed to update class");

        return ['success' => true, 'message' => 'Class updated successfully'];
    }

    public function deleteClass($id) {
        $existing = $this->classDao->getById($id);
        if (!$existing) throw new Exception("Class not found");

        $result = $this->classDao->delete($id);
        if (!$result) throw new Exception("Failed to delete class");

        return ['success' => true, 'message' => 'Class deleted successfully'];
    }

    public function getSchedule() {
    
    $classes = $this->classDao->getAll();

    
    usort($classes, function($a, $b) {
        return strtotime($a['schedule_time']) - strtotime($b['schedule_time']);
    });

    return $classes;
}

}
?>
