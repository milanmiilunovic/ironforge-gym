<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/rest/dao/BaseDao.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// ==== INCLUDE SERVICES ==== //
require_once __DIR__ . '/rest/services/UserService.php';
require_once __DIR__ . '/rest/services/UserAuthService.php';
require_once __DIR__ . '/rest/services/BookingService.php';
require_once __DIR__ . '/rest/services/MembershipService.php';
require_once __DIR__ . '/rest/services/ClassService.php';
require_once __DIR__ . '/rest/services/TrainerService.php';
require_once __DIR__ . '/rest/services/CategoryService.php';

// ==== INCLUDE MIDDLEWARE & ROLES ==== //
require_once __DIR__ . '/middleware/AuthMiddleware.php';


header('Access-Control-Allow-Origin: *'); // Or put your specific frontend URL here
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// ==== REGISTER SERVICES ==== //
Flight::register('userService', 'UserService');
Flight::register('userAuthService', 'UserAuthService'); // Auth service
Flight::register('bookingService', 'BookingService');
Flight::register('membershipService', 'MembershipService');
Flight::register('classService', 'ClassService');
Flight::register('trainerService', 'TrainerService');
Flight::register('categoryService', 'CategoryService');
Flight::register('authMiddleware', 'AuthMiddleware'); // Middleware

// ==== GLOBAL MIDDLEWARE (SECURITY) ==== //
Flight::before('start', function () {
    $url = Flight::request()->url;

    // 1. Whitelist Public Routes
    if (str_starts_with($url, '/auth/login') || str_starts_with($url, '/auth/register') || str_starts_with($url, '/v1/docs') || $url === '/' || $url === '/health' || $url = "/trainers" || $url = "/classes") {
        return; // Continue to next route
    }

    // 2. Get the Authorization Header
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

    if (!$authHeader) {
        Flight::halt(401, json_encode(['message' => 'Missing Authorization Header']));
    }

    // 3. Remove "Bearer " prefix if present
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
    } else {
        $token = $authHeader; // Fallback if just the token is sent
    }

    try {
        // 4. Verify Token validity
        Flight::authMiddleware()->verifyToken($token);

        // 5. Admin Route Protection
        if (str_starts_with($url, '/admin/')) {
            if (!Flight::authMiddleware()->verifyIsAdmin()) {
                Flight::halt(403, json_encode(['message' => 'Forbidden: Admin access required']));
            }
        }
        
    } catch (\Exception $e) {
        Flight::halt(401, json_encode(['message' => $e->getMessage()]));
    }
});

// ==== INCLUDE ROUTES ==== //
require_once __DIR__ . '/rest/routes/AuthRoutes.php'; // Auth routes first
require_once __DIR__ . '/rest/routes/healthRoute.php';
require_once __DIR__ . '/rest/routes/adminRoutes.php';
require_once __DIR__ . '/rest/routes/userRoutes.php';
require_once __DIR__ . '/rest/routes/membershipRoutes.php';
require_once __DIR__ . '/rest/routes/classRoutes.php';
require_once __DIR__ . '/rest/routes/trainerRoutes.php';
require_once __DIR__ . '/rest/routes/categoryRoutes.php';
require_once __DIR__ . '/rest/routes/bookingRoutes.php';

// ==== DEFAULT ROUTE ==== //
Flight::route('/allah', function() {
    echo 'API is running 🚀';
});

// ==== START FLIGHT ==== //
Flight::start();
