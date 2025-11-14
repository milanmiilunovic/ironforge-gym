<?php
require_once __DIR__ . '/UserAuthService.php';
require_once __DIR__ . '/UserProfileService.php';
require_once __DIR__ . '/UserMembershipService.php';
require_once __DIR__ . '/UserAdminService.php';
require_once __DIR__ . '/../dao/BookingDao.php';



class UserService {
    private $authService;
    private $profileService;
    private $membershipService;
    private $adminService;
    private $bookingDao;

    public function __construct() {
        $this->authService = new UserAuthService();
        $this->profileService = new UserProfileService();
        $this->membershipService = new UserMembershipService();
        $this->adminService = new UserAdminService();
        $this->bookingDao = new BookingDao();
    }

    /* ===== AUTH ===== */
    public function registerUser($userData) {
        return $this->authService->registerUser($userData);
    }

    public function login($email, $password) {
        return $this->authService->login($email, $password);
    }

    /* ===== PROFILE ===== */
    public function getUserProfile($userId) {
        return $this->profileService->getUserProfile($userId);
    }

    public function updateProfile($userId, $updates) {
        return $this->profileService->updateProfile($userId, $updates);
    }

    public function changePassword($userId, $oldPassword, $newPassword) {
        return $this->profileService->changePassword($userId, $oldPassword, $newPassword);
    }

    /* ===== MEMBERSHIP ===== */
    public function hasActiveMembership($userId) {
        return $this->membershipService->hasActiveMembership($userId);
    }

    public function getUserMemberships($userId) {
        return $this->membershipService->getUserMemberships($userId);
    }

    /* ===== ADMIN ===== */
    public function getAllUsers() {
        return $this->adminService->getAllUsers();
    }

    public function deleteUser($userId) {
        return $this->adminService->deleteUser($userId);
    }

    /* ===== STATS ===== */
    public function getUserStats($userId) {
        $bookings = $this->bookingDao->getByUser($userId);
        $activeBookings = $this->bookingDao->getUserActiveBookings($userId);
        $memberships = $this->membershipService->getUserMemberships($userId);
        $activeMembership = $this->membershipService->getActiveMembership($userId);

        return [
            'total_bookings' => count($bookings),
            'active_bookings' => count($activeBookings),
            'total_memberships' => count($memberships),
            'has_active_membership' => $activeMembership ? true : false,
            'membership_expires' => $activeMembership['end_date'] ?? null
        ];
    }
}
?>
