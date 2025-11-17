<?php
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/UserMembershipDao.php';

class UserProfileService {
    private $userDao;
    private $userMembershipDao;

    public function __construct() {
        $this->userDao = new UserDao();
        $this->userMembershipDao = new UserMembershipDao();
    }

    // Get user profile
    public function getUserProfile($userId) {
        $user = $this->userDao->getById($userId);
        if (!$user) throw new Exception("User not found");

        unset($user['password_hash']); // uklonimo osjetljive podatke
        $user['active_membership'] = $this->userMembershipDao->getActiveMembership($userId);
        return $user;
    }

    // Update profile
    public function updateProfile($userId, $updates) {
        if (isset($updates['email'])) {
            if (!filter_var($updates['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email address");
            }

            $existingUser = $this->userDao->getByEmail($updates['email']);
            if ($existingUser && $existingUser['user_id'] != $userId) {
                throw new Exception("Email already in use");
            }
        }

        // Uklonimo polje password ako je slučajno poslano
        if (isset($updates['password'])) {
            unset($updates['password']);
        }

        $result = $this->userDao->update($userId, $updates);
        if (!$result) throw new Exception("Update failed");

        return [
            'success' => true,
            'message' => 'Profile updated successfully'
        ];
    }

    // Change password
    public function changePassword($userId, $currentPassword, $newPassword) {
        if (empty($currentPassword) || empty($newPassword)) {
            throw new Exception("Missing required fields");
        }

        if (strlen($newPassword) < 6) {
            throw new Exception("New password must be at least 6 characters");
        }

        $user = $this->userDao->getById($userId);
        if (!$user) throw new Exception("User not found");

        if (!password_verify($currentPassword, $user['password_hash'])) {
            throw new Exception("Current password is incorrect");
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $result = $this->userDao->update($userId, ['password_hash' => $hashedPassword]);
        if (!$result) throw new Exception("Password change failed");

        return [
            'success' => true,
            'message' => 'Password changed successfully'
        ];
    }
}
?>
