<?php
require_once __DIR__ . '/../dao/UserMembershipDao.php';


class UserMembershipService {
    private $userMembershipDao;

    public function __construct() {
        $this->userMembershipDao = new UserMembershipDao();
    }

    public function getActiveMembership($userId) {
        return $this->userMembershipDao->getActiveMembership($userId);
    }

    public function hasActiveMembership($userId) {
        return $this->userMembershipDao->getActiveMembership($userId) ? true : false;
    }

    public function getUserMemberships($userId) {
        return $this->userMembershipDao->getByUser($userId);
    }
}
?>
