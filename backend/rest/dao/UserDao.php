<?php
require_once __DIR__ . '/BaseDao.php';


class UserDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("users");
    }

    public function getUserInfo($user_id)
    {
        $sql = "SELECT * FROM users WHERE user_id = :user_id";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindParam(':user_id', $user_id);

        $stmt->execute();

        $result = $stmt->fetch();
        return $result;
    }

    public function getByEmail($email)
    {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function deleteUser($id)
    {
        return $this->delete($id);
    }

}
