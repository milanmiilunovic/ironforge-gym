<?php
require_once __DIR__ . '/BaseDao.php';


class BookingDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("bookings");
    }

    public function getByUser($userId)
    {
        $stmt = $this->connection->prepare("
           SELECT b.*, c.title as class_title, c.*, t.* 
           FROM bookings b
           JOIN classes c ON b.class_id = c.class_id
           JOIN trainers t ON c.trainer_id = t.trainer_id
           WHERE b.user_id = :user_id 
           AND b.status = 'booked'
           ORDER BY c.schedule_time ASC
       ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByClass($classId)
    {
        $stmt = $this->connection->prepare("
           SELECT b.*, c.title, c.schedule_time, t.* 
           FROM bookings b
           JOIN classes c ON b.class_id = c.class_id
           JOIN trainers t ON c.trainer_id = t.trainer_id
           WHERE b.user_id = :user_id 
           AND b.status = 'booked'
           ORDER BY c.schedule_time ASC
       ");
        $stmt->bindParam(':class_id', $classId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function cancelBooking($bookingId)
    {

        echo `BOBOKINGID: ${bookingId}`;

        $stmt = $this->connection->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = :booking_id");
        $stmt->bindParam(':booking_id', $bookingId);



        return $stmt->execute();
    }

    public function getUserActiveBookings($userId)
    {
        $stmt = $this->connection->prepare("
           SELECT b.*, c.title, c.schedule_time, t.* 
           FROM bookings b
           JOIN classes c ON b.class_id = c.class_id
           JOIN trainers t ON c.trainer_id = t.trainer_id
           WHERE b.user_id = :user_id 
       ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUserClassBooking(int $userId, int $classId): ?array
    {
        $sql = "SELECT * FROM bookings 
            WHERE user_id = :user_id 
            AND class_id = :class_id 
            AND status != 'cancelled'
            LIMIT 1";

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':class_id' => $classId
            ]);

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $result ?: null;

        } catch (\PDOException $e) {
            return null;
        }
    }


    public function createUserBooking($userId, $classId)
    {
        $sql = "INSERT INTO bookings (user_id, class_id) VALUES (:user_id, :class_id)";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':class_id', $classId);

        return $stmt->execute();

    }

    public function checkIfUserBooked($userId, $classId)
    {
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