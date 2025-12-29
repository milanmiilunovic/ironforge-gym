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

// ==== CORS CONFIGURATION (MUST BE AT THE TOP) ==== //
// This tells the browser: "Allow the Walrus App to talk to this backend"
$allowed_origin = 'https://walrus-app-j9zhn.ondigitalocean.app';

header("Access-Control-Allow-Origin: $allowed_origin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

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
    $method = Flight::request()->method;

    // 1. Handle CORS Preflight (Browser Check)
    // If we don't return OK here, the browser will block the real request.
    if ($method === 'OPTIONS') {
        Flight::halt(200);
        return;
    }

    // 2. Whitelist Public Routes
    // I fixed the syntax error here (you had '$url =' which breaks the logic)
    if (str_starts_with($url, '/auth/login') ||
        str_starts_with($url, '/auth/register') ||
        str_starts_with($url, '/v1/docs') ||
        str_starts_with($url, '/bookings') ||
        $url === '/' ||
        $url === '/health' ||
        str_starts_with($url, '/trainers') ||
        str_starts_with($url, '/classes')) {
        return; // Continue to next route
    }

    // 3. Get the Authorization Header (Your original logic)
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;
    } else {
        // Fallback for Nginx if getallheaders() is missing
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
    }

    if (!$authHeader) {
        Flight::halt(401, json_encode(['message' => 'Missing Authorization Header']));
    }

    // 4. Remove "Bearer " prefix if present
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
    } else {
        $token = $authHeader;
    }

    try {
        // 5. Verify Token validity
        Flight::authMiddleware()->verifyToken($token);

        // 6. Admin Route Protection
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
require_once __DIR__ . '/rest/routes/authRoutes.php';
require_once __DIR__ . '/rest/routes/healthRoute.php';
require_once __DIR__ . '/rest/routes/adminRoutes.php';
require_once __DIR__ . '/rest/routes/userRoutes.php';
require_once __DIR__ . '/rest/routes/membershipRoutes.php';
require_once __DIR__ . '/rest/routes/classRoutes.php';
require_once __DIR__ . '/rest/routes/trainerRoutes.php';
require_once __DIR__ . '/rest/routes/categoryRoutes.php';
require_once __DIR__ . '/rest/routes/bookingRoutes.php';


// ==== START FLIGHT ==== //
Flight::start();