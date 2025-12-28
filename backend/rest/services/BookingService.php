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


        $result = $this->bookingDao->createUserBooking($data['user_id'], $data['class_id']);

        return ['success' => true, 'message' => 'Booking created successfully'];
    }



    public function cancelBooking($bookingId) {

        $result = $this->bookingDao->cancelBooking($bookingId);
        return ['success' => true, 'message' => 'Booking cancelled successfully'];
    }

    public function getActiveBookings($userId) {
        return $this->bookingDao->getUserActiveBookings($userId);
    }
}
