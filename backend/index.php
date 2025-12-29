<?php
require 'vendor/autoload.php';
require_once __DIR__ . '/rest/dao/BaseDao.php';

// ==== DATA TYPES & JWT ==== //
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

// ==== INCLUDE MIDDLEWARE ==== //
require_once __DIR__ . '/middleware/AuthMiddleware.php';

// =========================================================================
// 1. CORS CONFIGURATION (CRITICAL FOR DIGITALOCEAN)
// =========================================================================
// We dynamically allow your Frontend URL or Localhost (for when you test locally)
$allowed_origins = [
    "https://walrus-app-j9zhn.ondigitalocean.app", // Your DigitalOcean Frontend
    "http://localhost:3000",                         // Your Local Frontend (if using React/Vue local)
    "http://127.0.0.1:5500"                          // Your Local Frontend (if using Live Server)
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    // Fallback: If the origin isn't in the list, default to the DO App (or * if you are desperate)
    header("Access-Control-Allow-Origin: https://walrus-app-j9zhn.ondigitalocean.app");
}

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// =========================================================================
// 2. REGISTER SERVICES
// =========================================================================
Flight::register('userService', 'UserService');
Flight::register('userAuthService', 'UserAuthService');
Flight::register('bookingService', 'BookingService');
Flight::register('membershipService', 'MembershipService');
Flight::register('classService', 'ClassService');
Flight::register('trainerService', 'TrainerService');
Flight::register('categoryService', 'CategoryService');
Flight::register('authMiddleware', 'AuthMiddleware');

// =========================================================================
// 3. GLOBAL MIDDLEWARE (SECURITY & AUTH)
// =========================================================================
Flight::before('start', function () {
    $url = Flight::request()->url;
    $method = Flight::request()->method;

    // ---- A. HANDLE CORS PREFLIGHT (OPTIONS) ---- //
    // The browser sends an OPTIONS request before POST/PUT to check permissions.
    // We must return 200 OK immediately, otherwise Authentication will block it.
    if ($method === 'OPTIONS') {
        Flight::response()
            ->status(200)
            ->send();
        exit; // Stop processing here
    }

    // ---- B. PUBLIC ROUTES WHITELIST ---- //
    // These routes do NOT require a login token
    $publicRoutes = [
        '/auth/login',
        '/auth/register',
        '/v1/docs',
        '/',
        '/health',
        '/trainers', // Matches /trainers AND /trainers/123 due to logic below
        '/classes',  // Matches /classes AND /classes/123
        '/categories',
        '/memberships'
    ];

    $isPublic = false;
    foreach ($publicRoutes as $route) {
        // str_starts_with allows "/trainers/5" to pass if "/trainers" is in the list
        if (str_starts_with($url, $route)) {
            $isPublic = true;
            break;
        }
    }

    if ($isPublic) {
        return; // Proceed to the route logic without checking token
    }

    // ---- C. AUTHENTICATION (CHECK TOKEN) ---- //
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

    if (!$authHeader) {
        Flight::halt(401, json_encode(['message' => 'Unauthorized: Missing Authorization Header']));
    }

    // Extract Token from "Bearer <token>"
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
    } else {
        $token = $authHeader;
    }

    try {
        // Verify the token using your Middleware
        Flight::authMiddleware()->verifyToken($token);

        // ---- D. ADMIN ROLE CHECK ---- //
        // If the URL starts with /admin/, ensure the user has admin role
        if (str_starts_with($url, '/admin/')) {
            if (!Flight::authMiddleware()->verifyIsAdmin()) {
                Flight::halt(403, json_encode(['message' => 'Forbidden: Admin privileges required']));
            }
        }

    } catch (\Exception $e) {
        Flight::halt(401, json_encode(['message' => 'Authentication Failed: ' . $e->getMessage()]));
    }
});

// =========================================================================
// 4. INCLUDE ROUTES
// =========================================================================
require_once __DIR__ . '/rest/routes/AuthRoutes.php';
require_once __DIR__ . '/rest/routes/healthRoute.php';
require_once __DIR__ . '/rest/routes/adminRoutes.php';
require_once __DIR__ . '/rest/routes/userRoutes.php';
require_once __DIR__ . '/rest/routes/membershipRoutes.php';
require_once __DIR__ . '/rest/routes/classRoutes.php';
require_once __DIR__ . '/rest/routes/trainerRoutes.php';
require_once __DIR__ . '/rest/routes/categoryRoutes.php';
require_once __DIR__ . '/rest/routes/bookingRoutes.php';

// =========================================================================
// 5. ERROR HANDLING & START
// =========================================================================

// Custom 404 handler so it returns JSON instead of HTML
Flight::map('notFound', function(){
    Flight::halt(404, json_encode(['message' => 'Route not found']));
});

// Generic 500 Error handler
Flight::map('error', function(Exception $ex){
    // Log the error on the server side (optional)
    // error_log($ex->getMessage());
    Flight::halt(500, json_encode(['message' => 'Internal Server Error', 'error' => $ex->getMessage()]));
});

// Start the API
Flight::start();