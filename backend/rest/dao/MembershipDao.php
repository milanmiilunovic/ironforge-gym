<?php
require_once __DIR__ . '/BaseDao.php';


class MembershipDao extends BaseDao {
   public function __construct() {
       parent::__construct("memberships");
   }

   public function getByPriceRange($minPrice, $maxPrice) {
       $stmt = $this->connection->prepare("SELECT * FROM memberships WHERE price BETWEEN :min AND :max ORDER BY price ASC");
       $stmt->bindParam(':min', $minPrice);
       $stmt->bindParam(':max', $maxPrice);
       $stmt->execute();
       return $stmt->fetchAll();
   }

   public function getByDuration($months) {
       $stmt = $this->connection->prepare("SELECT * FROM memberships WHERE duration_months = :months");
       $stmt->bindParam(':months', $months);
       $stmt->execute();
       return $stmt->fetchAll();
   }

   public function getActiveMemberships() {
       $stmt = $this->connection->prepare("SELECT * FROM memberships WHERE price > 0 ORDER BY price ASC");
       $stmt->execute();
       return $stmt->fetchAll();
   }
}
?>