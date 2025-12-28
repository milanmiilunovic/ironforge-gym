<?php
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/TrainerService.php';
require_once __DIR__ . '/../services/MembershipService.php';
require_once __DIR__ . '/../services/CategoryService.php';

/**
 * @OA\Group(
 *     path="/admin",
 *     summary="Admin routes",
 *     description="Routes for admin users"
 * )
 */
Flight::group("/admin", function(){

    /**
     * @OA\Get(
     *     path="/test",
     *     tags={"Admin - General"},
     *     summary="Test the admin endpoint and the JWTs",
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         description="Token",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="If it returns 200, the JWT is admin JWT."
     *     )
     * )
     */
    Flight::route("GET /test", function(){
        Flight::json("Working admin!");
    });

    // ==========================================
    // ====          ADMIN - CLASSES         ====
    // ==========================================

    /**
     * @OA\Post(
     *     path="/classes",
     *     tags={"Admin - Classes"},
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
     *     @OA\Response(response=200, description="Fitness class created successfully")
     * )
     */
    Flight::route('POST /classes', function() {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::classService()->createClass($data));
    });

    /**
     * @OA\Put(
     *     path="/classes/{id}",
     *     tags={"Admin - Classes"},
     *     summary="Update an existing fitness class",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(@OA\Property(property="title", type="string", example="Updated Yoga Class"))
     *     ),
     *     @OA\Response(response=200, description="Class updated")
     * )
     */
    Flight::route('PUT /classes/@id', function($id) {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::classService()->updateClass($id, $data));
    });

    /**
     * @OA\Delete(
     *     path="/classes/{id}",
     *     tags={"Admin - Classes"},
     *     summary="Delete a fitness class",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Class deleted")
     * )
     */
    Flight::route('DELETE /classes/@id', function($id) {
        Flight::json(Flight::classService()->deleteClass($id));
    });

    // ==========================================
    // ====           ADMIN - USERS          ====
    // ==========================================

    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"Admin - Users"},
     *     summary="Get all users",
     *     @OA\Response(response=200, description="Returns all users")
     * )
     */
    Flight::route('GET /users', function() {
        Flight::json(Flight::userService()->getAllUsers());
    });

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     tags={"Admin - Users"},
     *     summary="Delete a user by ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User deleted successfully")
     * )
     */
    Flight::route('DELETE /users/@id', function($id) {
        Flight::json(Flight::userService()->deleteUser($id));
    });

    // ==========================================
    // ====          ADMIN - TRAINERS        ====
    // ==========================================

    /**
     * @OA\Post(
     *     path="/trainers",
     *     tags={"Admin - Trainers"},
     *     summary="Create a new trainer",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"full_name","email"},
     *             @OA\Property(property="full_name", type="string", example="John Doe")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Trainer created")
     * )
     */
    Flight::route('POST /trainers', function() {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::trainerService()->createTrainer($data));
    });

    /**
     * @OA\Put(
     *     path="/trainers/{id}",
     *     tags={"Admin - Trainers"},
     *     summary="Update trainer by ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(@OA\Property(property="full_name", type="string", example="Updated Name"))
     *     ),
     *     @OA\Response(response=200, description="Trainer updated")
     * )
     */
    Flight::route('PUT /trainers/@id', function($id) {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::trainerService()->updateTrainer($id, $data));
    });

    /**
     * @OA\Delete(
     *     path="/trainers/{id}",
     *     tags={"Admin - Trainers"},
     *     summary="Delete a trainer by ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Trainer deleted")
     * )
     */
    Flight::route('DELETE /trainers/@id', function($id) {
        Flight::json(Flight::trainerService()->deleteTrainer($id));
    });

    // ==========================================
    // ====        ADMIN - MEMBERSHIPS       ====
    // ==========================================

    /**
     * @OA\Get(
     *     path="/memberships",
     *     tags={"Admin - Memberships"},
     *     summary="Get all memberships",
     *     @OA\Response(response=200, description="List of all memberships")
     * )
     */
    Flight::route('GET /memberships', function() {
        Flight::json(Flight::membershipService()->getAllMemberships());
    });

    /**
     * @OA\Delete(
     *     path="/memberships/{id}",
     *     tags={"Admin - Memberships"},
     *     summary="Delete a membership by ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Membership deleted")
     * )
     */
    Flight::route('DELETE /memberships/@id', function($id) {
        Flight::json(Flight::membershipService()->deleteMembership($id));
    });

    // ==========================================
    // ====         ADMIN - CATEGORIES       ====
    // ==========================================

    /**
     * @OA\Post(
     *     path="/categories",
     *     tags={"Admin - Categories"},
     *     summary="Create a new category",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Yoga")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Category created")
     * )
     */
    Flight::route('POST /categories', function() {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::categoryService()->createCategory($data));
    });

    /**
     * @OA\Put(
     *     path="/categories/{id}",
     *     tags={"Admin - Categories"},
     *     summary="Update an existing category",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(@OA\Property(property="name", type="string", example="Updated Category"))
     *     ),
     *     @OA\Response(response=200, description="Category updated")
     * )
     */
    Flight::route('PUT /categories/@id', function($id) {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::categoryService()->updateCategory($id, $data));
    });

    /**
     * @OA\Delete(
     *     path="/categories/{id}",
     *     tags={"Admin - Categories"},
     *     summary="Delete a category",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Category deleted")
     * )
     */
    Flight::route('DELETE /categories/@id', function($id) {
        Flight::json(Flight::categoryService()->deleteCategory($id));
    });

});