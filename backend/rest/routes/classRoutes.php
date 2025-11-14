<?php
require_once __DIR__ . '/../services/ClassService.php';

/**
 * @OA\Post(
 *     path="/classes",
 *     tags={"classes"},
 *     summary="Create a new fitness class",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title","trainer_id","category_id","schedule_time","duration_minutes","capacity"},
 *             @OA\Property(property="title", type="string", example="Yoga Basics"),
 *             @OA\Property(property="trainer_id", type="integer", example=1),
 *             @OA\Property(property="category_id", type="integer", example=2),
 *             @OA\Property(property="schedule_time", type="string", format="date-time", example="2025-11-20 10:00:00"),
 *             @OA\Property(property="duration_minutes", type="integer", example=60),
 *             @OA\Property(property="capacity", type="integer", example=20),
 *             @OA\Property(property="description", type="string", example="Beginner-friendly yoga session")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Fitness class created successfully"
 *     )
 * )
 */
Flight::route('POST /classes', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::classService()->createClass($data));
});

/**
 * @OA\Put(
 *     path="/classes/{id}",
 *     tags={"classes"},
 *     summary="Update an existing fitness class",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the class",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="Updated Yoga Class"),
 *             @OA\Property(property="trainer_id", type="integer", example=2),
 *             @OA\Property(property="category_id", type="integer", example=3),
 *             @OA\Property(property="schedule_time", type="string", format="date-time", example="2025-11-21 11:00:00"),
 *             @OA\Property(property="duration_minutes", type="integer", example=75),
 *             @OA\Property(property="capacity", type="integer", example=25),
 *             @OA\Property(property="description", type="string", example="Updated description")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Class updated successfully"
 *     )
 * )
 */
Flight::route('PUT /classes/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::classService()->updateClass($id, $data));
});

/**
 * @OA\Delete(
 *     path="/classes/{id}",
 *     tags={"classes"},
 *     summary="Delete a fitness class",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the class",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Class deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /classes/@id', function($id) {
    Flight::json(Flight::classService()->deleteClass($id));
});

/**
 * @OA\Get(
 *     path="/classes",
 *     tags={"classes"},
 *     summary="Get all fitness classes",
 *     @OA\Response(
 *         response=200,
 *         description="List of all classes"
 *     )
 * )
 */
Flight::route('GET /classes', function() {
    Flight::json(Flight::classService()->getAllClasses());
});

/**
 * @OA\Get(
 *     path="/classes/schedule",
 *     tags={"classes"},
 *     summary="Get the schedule of classes",
 *     @OA\Response(
 *         response=200,
 *         description="Returns the schedule for all classes"
 *     )
 * )
 */
Flight::route('GET /classes/schedule', function() {
    Flight::json(Flight::classService()->getSchedule());
});

/**
 * @OA\Get(
 *     path="/classes/{id}",
 *     tags={"classes"},
 *     summary="Get class details by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the class",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Details of the class"
 *     )
 * )
 */
Flight::route('GET /classes/@id', function($id) {
    Flight::json(Flight::classService()->getClassById($id));  
});

?>
