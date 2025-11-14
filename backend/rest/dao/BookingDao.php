<?php
require_once __DIR__ . '/BaseDao.php';


class BookingDao extends BaseDao {
   public function __construct() {
       parent::__construct("bookings");
   }

   public function getByUser($userId) {
       $stmt = $this->connection->prepare("
           SELECT b.*, c.title as class_title, c.schedule_time 
           FROM bookings b
           JOIN classes c ON b.class_id = c.class_id
           WHERE b.user_id = :user_id 
           ORDER BY b.booking_date DESC
       ");
       $stmt->bindParam(':user_id', $userId);
       $stmt->execute();
       return $stmt->fetchAll();
   }

   public function getByClass($classId) {
       $stmt = $this->connection->prepare("
           SELECT b.*, u.full_name, u.email 
           FROM bookings b
           JOIN users u ON b.user_id = u.user_id
           WHERE b.class_id = :class_id AND b.status = 'booked'
       ");
       $stmt->bindParam(':class_id', $classId);
       $stmt->execute();
       return $stmt->fetchAll();
   }

   public function cancelBooking($bookingId) {
       $stmt = $this->connection->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = :booking_id");
       $stmt->bindParam(':booking_id', $bookingId);
       return $stmt->execute();
   }

   public function getUserActiveBookings($userId) {
       $stmt = $this->connection->prepare("
           SELECT b.*, c.title, c.schedule_time 
           FROM bookings b
           JOIN classes c ON b.class_id = c.class_id
           WHERE b.user_id = :user_id 
           AND b.status = 'booked'
           AND c.schedule_time > NOW()
           ORDER BY c.schedule_time ASC
       ");
       $stmt->bindParam(':user_id', $userId);
       $stmt->execute();
       return $stmt->fetchAll();
   }

   public function checkIfUserBooked($userId, $classId) {
       $stmt = $this->connection->prepare("
           SELECT * FROM bookings 
           WHERE user_id = :user_id 
           AND class_id = :class_id 
           AND status = 'booked'
       ");
       $stmt->bindParam(':user_id', $userId);
       $stmt->bindParam(':class_id', $classId);
       $stmt->execute();
       return $stmt->fetch();
   }
}
?>