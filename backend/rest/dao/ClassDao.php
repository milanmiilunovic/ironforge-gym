<?php
require_once 'BaseDao.php';

class ClassDao extends BaseDao {
   public function __construct() {
       parent::__construct("classes");
   }

   public function getByTrainer($trainerId) {
       $stmt = $this->connection->prepare("SELECT * FROM classes WHERE trainer_id = :trainer_id ORDER BY schedule_time ASC");
       $stmt->bindParam(':trainer_id', $trainerId);
       $stmt->execute();
       return $stmt->fetchAll();
   }

   public function getByCategory($categoryId) {
       $stmt = $this->connection->prepare("SELECT * FROM classes WHERE category_id = :category_id ORDER BY schedule_time ASC");
       $stmt->bindParam(':category_id', $categoryId);
       $stmt->execute();
       return $stmt->fetchAll();
   }

   public function getUpcomingClasses() {
       $stmt = $this->connection->prepare("SELECT * FROM classes WHERE schedule_time > NOW() ORDER BY schedule_time ASC");
       $stmt->execute();
       return $stmt->fetchAll();
   }

   public function getClassWithDetails($classId) {
       $stmt = $this->connection->prepare("
           SELECT c.*, t.full_name as trainer_name, cat.name as category_name 
           FROM classes c
           LEFT JOIN trainers t ON c.trainer_id = t.trainer_id
           LEFT JOIN categories cat ON c.category_id = cat.category_id
           WHERE c.class_id = :class_id
       ");
       $stmt->bindParam(':class_id', $classId);
       $stmt->execute();
       return $stmt->fetch();
   }

   public function getAvailableClasses() {
       $stmt = $this->connection->prepare("
           SELECT c.*, (c.capacity - COUNT(b.booking_id)) as available_spots
           FROM classes c
           LEFT JOIN bookings b ON c.class_id = b.class_id AND b.status = 'booked'
           WHERE c.schedule_time > NOW()
           GROUP BY c.class_id
           HAVING available_spots > 0
           ORDER BY c.schedule_time ASC
       ");
       $stmt->execute();
       return $stmt->fetchAll();
   }
}
?>