<?php
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;

class UserAuthService {
    private $userDao;

    public function __construct() {
        $this->userDao = new UserDao();
    }

    // Register new user
    public function registerUser($userData) {
        if (empty($userData['email']) || !filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address");
        }

        if (empty($userData['password']) || strlen($userData['password']) < 6) {
            throw new Exception("Password must be at least 6 characters");
        }

        if (empty($userData['full_name'])) {
            throw new Exception("Full name is required");
        }

        $existingUser = $this->userDao->getByEmail($userData['email']);
        if ($existingUser) {
            throw new Exception("Email already registered");
        }

        $userData['password_hash'] = password_hash($userData['password'], PASSWORD_BCRYPT);
        unset($userData['password']);

        $result = $this->userDao->insert($userData);
        if (!$result) {
            throw new Exception("Registration failed");
        }

        return [
            'success' => true,
            'message' => 'User registered successfully'
        ];
    }

    // Login user + JWT
    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            throw new Exception("Email and password are required");
        }

        $user = $this->userDao->getByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            throw new Exception("Invalid email or password");
        }

        unset($user['password_hash']);

        // JWT payload
        $payload = [
            'user' => $user,
            'iat' => time(),
            'exp' => time() + (60*60*24) 
        ];

        $token = JWT::encode($payload, Config::JWT_SECRET(), 'HS256');

        return [
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ];
    }
}
