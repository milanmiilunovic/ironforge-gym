<?php
require_once __DIR__ . '/../dao/MembershipDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/UserMembershipDao.php'; 

class MembershipService {
    private $membershipDao;
    private $userDao;
    private $userMembershipDao;

    public function __construct() {
        $this->membershipDao = new MembershipDao();
        $this->userDao = new UserDao();
        $this->userMembershipDao = new UserMembershipDao();
    }

    public function getAllMemberships() {
        return $this->membershipDao->getAll();
    }

    public function getMembershipById($id) {
        $membership = $this->membershipDao->getById($id);
        if (!$membership) throw new Exception("Membership not found");
        return $membership;
    }

    public function createMembership($data) {
        if (empty($data['name']) || empty($data['price']) || empty($data['duration_months'])) {
            throw new Exception("Missing required fields");
        }
        $result = $this->membershipDao->insert($data);
        if (!$result) throw new Exception("Failed to create membership");
        return ['success' => true, 'message' => 'Membership created successfully'];
    }

    public function updateMembership($id, $data) {
        $existing = $this->membershipDao->getById($id);
        if (!$existing) throw new Exception("Membership not found");

        $result = $this->membershipDao->update($id, $data);
        if (!$result) throw new Exception("Failed to update membership");
        return ['success' => true, 'message' => 'Membership updated successfully'];
    }

    public function deleteMembership($id) {
        $existing = $this->membershipDao->getById($id);
        if (!$existing) throw new Exception("Membership not found");

        $result = $this->membershipDao->delete($id);
        if (!$result) throw new Exception("Failed to delete membership");
        return ['success' => true, 'message' => 'Membership deleted successfully'];
    }

    
    public function purchaseMembership($data) {
        if (empty($data['user_id']) || empty($data['membership_id']) || empty($data['start_date'])) {
            throw new Exception("Missing required fields");
        }
    
        $user = $this->userDao->getById($data['user_id']);
        if (!$user) throw new Exception("Invalid user");

        $membership = $this->membershipDao->getById($data['membership_id']);
        if (!$membership) throw new Exception("Invalid membership");

        $startDate = new DateTime($data['start_date']);
        $endDate = clone $startDate;
        $endDate->modify('+' . $membership['duration_months'] . ' months');

        $purchaseData = [
            'user_id' => $data['user_id'],
            'membership_id' => $data['membership_id'],
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d')
        ];

        $result = $this->userMembershipDao->insert($purchaseData);
        if (!$result) throw new Exception("Failed to purchase membership");

        return ['success' => true, 'message' => 'Membership purchased successfully'];
    }
        public function getUserMemberships($userId) {
        $user = $this->userDao->getById($userId);
        if (!$user) throw new Exception("User not found");

        return $this->userMembershipDao->getByUserId($userId);
    }
        public function getActiveMembership($userId) {
        $user = $this->userDao->getById($userId);
        if (!$user) throw new Exception("Invalid user");

        $activeMembership = $this->userMembershipDao->getActiveMembership($userId);
        if (!$activeMembership) throw new Exception("No active membership found for this user");

        return $activeMembership;
    }


}
?>
