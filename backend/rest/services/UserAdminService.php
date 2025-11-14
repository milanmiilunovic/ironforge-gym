<?php
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/BookingDao.php';


class UserAdminService {
    private $userDao;
    private $bookingDao;

    public function __construct() {
        $this->userDao = new UserDao();
        $this->bookingDao = new BookingDao();
    }

    // Get all users
    public function getAllUsers() {
        return $this->userDao->getAll();
    }

    // Delete user
    public function deleteUser($userId) {
        $user = $this->userDao->getById($userId);
        if (!$user) throw new Exception("User not found");

        $activeBookings = $this->bookingDao->getUserActiveBookings($userId);
        if (count($activeBookings) > 0) {
            throw new Exception("Cannot delete user with active bookings");
        }

        $result = $this->userDao->deleteUser($userId);
        if (!$result) throw new Exception("Delete failed");

        return [
            'success' => true,
            'message' => 'User deleted successfully'
        ];
    }
}
?>
