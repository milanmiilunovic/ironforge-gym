<?php
require_once __DIR__ . '/../services/TrainerService.php';

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

/**
 * @OA\Post(
 *     path="/trainers",
 *     tags={"trainers"},
 *     summary="Create a new trainer",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"full_name","specialization","email"},
 *             @OA\Property(property="full_name", type="string", example="John Doe"),
 *             @OA\Property(property="specialization", type="string", example="Strength Training"),
 *             @OA\Property(property="phone", type="string", example="123456789"),
 *             @OA\Property(property="email", type="string", example="john@example.com"),
 *             @OA\Property(property="experience_years", type="integer", example=5)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Trainer created successfully"
 *     )
 * )
 */
Flight::route('POST /trainers', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::trainerService()->createTrainer($data));
});

/**
 * @OA\Put(
 *     path="/trainers/{id}",
 *     tags={"trainers"},
 *     summary="Update trainer by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the trainer",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="full_name", type="string", example="Updated Name"),
 *             @OA\Property(property="specialization", type="string", example="Yoga"),
 *             @OA\Property(property="phone", type="string", example="987654321"),
 *             @OA\Property(property="email", type="string", example="updated@example.com"),
 *             @OA\Property(property="experience_years", type="integer", example=4)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Trainer updated successfully"
 *     )
 * )
 */
Flight::route('PUT /trainers/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::trainerService()->updateTrainer($id, $data));
});

/**
 * @OA\Delete(
 *     path="/trainers/{id}",
 *     tags={"trainers"},
 *     summary="Delete a trainer by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the trainer",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Trainer deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /trainers/@id', function($id) {
    Flight::json(Flight::trainerService()->deleteTrainer($id));
});

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
    Flight::json(Flight::trainerService()->getTrainerAvailability($id));
});
?>
