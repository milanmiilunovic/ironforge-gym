<?php
require_once __DIR__ . '/../services/TrainerService.php';
require_once __DIR__ . '/../../data/roles.php';

// ==== GET ALL TRAINERS ==== //
/**
 * @OA\Get(
 *     path="/trainers",
 *     tags={"trainers"},
 *     summary="Get all trainers",
 *     @OA\Response(
 *         response=200,
 *         description="Returns a list of all trainers"
 *     )
 * )
 */
Flight::route('GET /trainers', function() {
    Flight::json(Flight::trainerService()->getAllTrainers());
});

// ==== GET TRAINER BY ID ==== //
/**
 * @OA\Get(
 *     path="/trainers/{id}",
 *     tags={"trainers"},
 *     summary="Get trainer by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the trainer",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the trainer"
 *     )
 * )
 */
Flight::route('GET /trainers/@id', function($id) {
    Flight::json(Flight::trainerService()->getTrainerById($id));
});

// ==== GET TRAINER AVAILABILITY ==== //
/**
 * @OA\Get(
 *     path="/trainers/{id}/availability",
 *     tags={"trainers"},
 *     summary="Get trainer availability",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the trainer",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns trainer availability schedule"
 *     )
 * )
 */
Flight::route('GET /trainers/@id/availability', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::USER);
    Flight::json(Flight::trainerService()->getTrainerAvailability($id));
});
