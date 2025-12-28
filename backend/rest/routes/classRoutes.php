<?php
require_once __DIR__ . '/../services/ClassService.php';
require_once __DIR__ . '/../../data/roles.php';



// ==== GET ALL CLASSES  ==== //
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




// ==== GET CLASSES SCHEDULE  ==== //
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

// ==== GET CLASS BY ID  ==== //
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
