<?php
require_once __DIR__ . '/BaseDao.php';

class UserMembershipDao extends BaseDao {

    public function __construct() {
        parent::__construct("user_memberships");
    }

    public function getByUser($userId) {
        $stmt = $this->connection->prepare("
            SELECT um.*, m.name as membership_name, m.price 
            FROM user_memberships um
            JOIN memberships m ON um.membership_id = m.membership_id
            WHERE um.user_id = :user_id
            ORDER BY um.start_date DESC
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveMembership($userId) {
        $stmt = $this->connection->prepare("
            SELECT um.*, m.name, m.description, m.price 
            FROM user_memberships um
            JOIN memberships m ON um.membership_id = m.membership_id
            WHERE um.user_id = :user_id 
            AND um.end_date >= CURDATE()
            ORDER BY um.end_date DESC
            LIMIT 1
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getExpiredMemberships($userId) {
        $stmt = $this->connection->prepare("
            SELECT um.*, m.name 
            FROM user_memberships um
            JOIN memberships m ON um.membership_id = m.membership_id
            WHERE um.user_id = :user_id 
            AND um.end_date < CURDATE()
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId($userId) {
        $stmt = $this->connection->prepare("SELECT * FROM user_memberships WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
