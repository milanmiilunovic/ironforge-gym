<?php
require_once 'BaseDao.php';

class TrainerDao extends BaseDao {
   public function __construct() {
       parent::__construct("trainers");
   }

   public function getByEmail($email) {
       $stmt = $this->connection->prepare("SELECT * FROM trainers WHERE email = :email");
       $stmt->bindParam(':email', $email);
       $stmt->execute();
       return $stmt->fetch();
   }

   public function getBySpecialization($specialization) {
       $stmt = $this->connection->prepare("SELECT * FROM trainers WHERE specialization = :specialization");
       $stmt->bindParam(':specialization', $specialization);
       $stmt->execute();
       return $stmt->fetchAll();
   }

   public function getTrainersByExperience($minYears) {
       $stmt = $this->connection->prepare("SELECT * FROM trainers WHERE experience_years >= :min_years ORDER BY experience_years DESC");
       $stmt->bindParam(':min_years', $minYears);
       $stmt->execute();
       return $stmt->fetchAll();
   }
}
?>