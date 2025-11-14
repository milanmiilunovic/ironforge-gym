<?php
require_once __DIR__ . '/../services/UserService.php';

/**
 * @OA\Post(
 *     path="/users/register",
 *     tags={"users"},
 *     summary="Register a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password","full_name"},
 *             @OA\Property(property="email", type="string", example="testuser@example.com"),
 *             @OA\Property(property="password", type="string", example="test123"),
 *             @OA\Property(property="full_name", type="string", example="Test User")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User registered successfully"
 *     )
 * )
 */
Flight::route('POST /users/register', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->registerUser($data));
});

/**
 * @OA\Post(
 *     path="/users/login",
 *     tags={"users"},
 *     summary="Login a user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", example="testuser@example.com"),
 *             @OA\Property(property="password", type="string", example="test123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful, returns user data"
 *     )
 * )
 */
Flight::route('POST /users/login', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->login($data['email'], $data['password']));
});

/**
 * @OA\Get(
 *     path="/users/{id}/profile",
 *     tags={"users"},
 *     summary="Get user profile by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns user profile"
 *     )
 * )
 */
Flight::route('GET /users/@id/profile', function($id) {
    Flight::json(Flight::userService()->getUserProfile($id));
});

/**
 * @OA\Put(
 *     path="/users/{id}/profile",
 *     tags={"users"},
 *     summary="Update user profile",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="full_name", type="string", example="Updated User"),
 *             @OA\Property(property="email", type="string", example="updated@example.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Profile updated successfully"
 *     )
 * )
 */
Flight::route('PUT /users/@id/profile', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->updateProfile($id, $data));
});

/**
 * @OA\Put(
 *     path="/users/{id}/password",
 *     tags={"users"},
 *     summary="Change user password",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"old_password","new_password"},
 *             @OA\Property(property="old_password", type="string", example="test123"),
 *             @OA\Property(property="new_password", type="string", example="newpass456")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password changed successfully"
 *     )
 * )
 */
Flight::route('PUT /users/@id/password', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->changePassword($id, $data['old_password'], $data['new_password']));
});

/**
 * @OA\Get(
 *     path="/users",
 *     tags={"users"},
 *     summary="Get all users (admin)",
 *     @OA\Response(
 *         response=200,
 *         description="Returns all users"
 *     )
 * )
 */
Flight::route('GET /users', function() {
    Flight::json(Flight::userService()->getAllUsers());
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Delete a user by ID (admin)",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /users/@id', function($id) {
    Flight::json(Flight::userService()->deleteUser($id));
});
?>
