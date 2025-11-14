<?php
require 'vendor/autoload.php';

// ==== INCLUDE SERVICES ==== //
require_once __DIR__ . '/rest/services/UserService.php';
require_once __DIR__ . '/rest/services/BookingService.php';
require_once __DIR__ . '/rest/services/MembershipService.php';
require_once __DIR__ . '/rest/services/ClassService.php';
require_once __DIR__ . '/rest/services/TrainerService.php';
require_once __DIR__ . '/rest/services/CategoryService.php';


// ==== REGISTER SERVICES ==== //
Flight::register('userService', 'UserService');
Flight::register('bookingService', 'BookingService');
Flight::register('membershipService', 'MembershipService');
Flight::register('classService', 'ClassService');
Flight::register('trainerService', 'TrainerService');
Flight::register('categoryService', 'CategoryService');

// ==== INCLUDE ROUTES ==== //
require_once __DIR__ . '/rest/routes/userRoutes.php';
require_once __DIR__ . '/rest/routes/membershipRoutes.php';
require_once __DIR__ . '/rest/routes/classRoutes.php';
require_once __DIR__ . '/rest/routes/trainerRoutes.php';
require_once __DIR__ . '/rest/routes/categoryRoutes.php';


// ==== DEFAULT TEST RUTA ==== //
Flight::route('/', function() {
    echo 'API is running 🚀';
});

// ==== START FLIGHT ==== //
Flight::start();
