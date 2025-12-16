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
require_once __DIR__ . '/data/roles.php';

// ==== REGISTER SERVICES ==== //
Flight::register('userService', 'UserService');
Flight::register('userAuthService', 'UserAuthService'); // Auth service
Flight::register('bookingService', 'BookingService');
Flight::register('membershipService', 'MembershipService');
Flight::register('classService', 'ClassService');
Flight::register('trainerService', 'TrainerService');
Flight::register('categoryService', 'CategoryService');
Flight::register('auth_middleware', 'AuthMiddleware'); // Middleware

// ==== JWT MIDDLEWARE ==== //
Flight::route('/*', function() {
    $url = Flight::request()->url;

    // Allow /auth routes without token
    if (strpos($url, '/auth/login') === 0 || strpos($url, '/auth/register') === 0) {
        return true;
    }

    try {
        $authHeader = Flight::request()->getHeader("Authorization");

        if (!$authHeader) {
            Flight::halt(401, "Missing authentication header");
        }

        // Extract token from "Bearer <token>"
        $token = trim(str_replace('Bearer', '', $authHeader));

        Flight::auth_middleware()->verifyToken($token);
    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
});

// ==== INCLUDE ROUTES ==== //
require_once __DIR__ . '/rest/routes/AuthRoutes.php'; // Auth routes first
require_once __DIR__ . '/rest/routes/userRoutes.php';
require_once __DIR__ . '/rest/routes/membershipRoutes.php';
require_once __DIR__ . '/rest/routes/classRoutes.php';
require_once __DIR__ . '/rest/routes/trainerRoutes.php';
require_once __DIR__ . '/rest/routes/categoryRoutes.php';

// ==== DEFAULT ROUTE ==== //
Flight::route('/', function() {
    echo 'API is running 🚀';
});

// ==== START FLIGHT ==== //
Flight::start();
