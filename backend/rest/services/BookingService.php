<?php
require_once __DIR__ . '/../dao/BookingDao.php';
require_once __DIR__ . '/../dao/ClassDao.php';
require_once __DIR__ . '/../dao/UserDao.php';


class BookingService {
    private $bookingDao;
    private $classDao;
    private $userDao;

    public function __construct() {
        $this->bookingDao = new BookingDao();
        $this->classDao = new ClassDao();
        $this->userDao = new UserDao();
    }

    public function getUserBookings($userId) {
        return $this->bookingDao->getByUser($userId);
    }

    public function getBookingById($id) {
        $booking = $this->bookingDao->getById($id);
        if (!$booking) throw new Exception("Booking not found");
        return $booking;
    }

    public function createBooking($data) {
        if (empty($data['user_id']) || empty($data['class_id'])) {
            throw new Exception("Missing user_id or class_id");
        }

        $class = $this->classDao->getById($data['class_id']);
        if (!$class) throw new Exception("Class not found");

        $user = $this->userDao->getById($data['user_id']);
        if (!$user) throw new Exception("User not found");

        // Check if user already booked the same class
        $existing = $this->bookingDao->getUserClassBooking($data['user_id'], $data['class_id']);
        if ($existing) throw new Exception("You already booked this class");

        $result = $this->bookingDao->insert($data);
        if (!$result) throw new Exception("Booking failed");

        return ['success' => true, 'message' => 'Booking created successfully'];
    }

    public function cancelBooking($bookingId) {
        $existing = $this->bookingDao->getById($bookingId);
        if (!$existing) throw new Exception("Booking not found");

        $result = $this->bookingDao->delete($bookingId);
        if (!$result) throw new Exception("Failed to cancel booking");

        return ['success' => true, 'message' => 'Booking cancelled successfully'];
    }

    public function getActiveBookings($userId) {
        return $this->bookingDao->getUserActiveBookings($userId);
    }
}
?>
