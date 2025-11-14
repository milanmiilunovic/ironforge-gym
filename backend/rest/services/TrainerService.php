<?php
require_once __DIR__ . '/../dao/TrainerDao.php';
require_once __DIR__ . '/../dao/ClassDao.php';

class TrainerService {
    private $trainerDao;
    private $classDao;

    public function __construct() {
        $this->trainerDao = new TrainerDao();
        $this->classDao = new ClassDao();
    }

    public function getAllTrainers() {
        return $this->trainerDao->getAll();
    }

    public function getTrainerById($id) {
        $trainer = $this->trainerDao->getById($id);
        if (!$trainer) throw new Exception("Trainer not found");
        return $trainer;
    }

    public function createTrainer($data) {
        
        if (empty($data['full_name']) || empty($data['email']) || empty($data['specialization'])) {
            throw new Exception("Missing required fields: full_name, email, specialization are required");
        }

        
        $insertData = [
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'specialization' => $data['specialization'],
            'phone' => isset($data['phone']) ? $data['phone'] : null,
            'experience_years' => isset($data['experience_years']) ? $data['experience_years'] : null
            
        ];

        
        $result = $this->trainerDao->insert($insertData);
        if (!$result) throw new Exception("Failed to create trainer");

        return ['success' => true, 'message' => 'Trainer created successfully'];
    }

    public function updateTrainer($id, $data) {
        $existing = $this->trainerDao->getById($id);
        if (!$existing) throw new Exception("Trainer not found");

        
        $updateData = [
            'full_name' => $data['full_name'] ?? $existing['full_name'],
            'email' => $data['email'] ?? $existing['email'],
            'specialization' => $data['specialization'] ?? $existing['specialization'],
            'phone' => $data['phone'] ?? $existing['phone'],
            'experience_years' => $data['experience_years'] ?? $existing['experience_years']
            
        ];

        $result = $this->trainerDao->update($id, $updateData);
        if (!$result) throw new Exception("Failed to update trainer");

        return ['success' => true, 'message' => 'Trainer updated successfully'];
    }

    public function deleteTrainer($id) {
        $existing = $this->trainerDao->getById($id);
        if (!$existing) throw new Exception("Trainer not found");

        
        $classes = $this->classDao->getByTrainer($id);
        if (!empty($classes)) throw new Exception("Cannot delete trainer with assigned classes");

        $result = $this->trainerDao->delete($id);
        if (!$result) throw new Exception("Failed to delete trainer");

        return ['success' => true, 'message' => 'Trainer deleted successfully'];
    }

        public function getTrainerAvailability($trainerId) {
        
        $trainer = $this->trainerDao->getById($trainerId);
        if (!$trainer) throw new Exception("Trainer not found");

        
        $classes = $this->classDao->getByTrainer($trainerId);

        return $classes; 
    }

}
?>
