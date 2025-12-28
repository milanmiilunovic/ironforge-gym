<?php
require_once __DIR__ . '/../services/CategoryService.php';
require_once __DIR__ . '/../../data/roles.php';

// ==== GET ALL CATEGORIES ==== //
/**
 * @OA\Get(
 *     path="/categories",
 *     tags={"categories"},
 *     summary="Get all categories",
 *     @OA\Response(
 *         response=200,
 *         description="List of all categories"
 *     )
 * )
 */
Flight::route('GET /categories', function() {
  Flight::json(Flight::categoryService()->getAllCategories());
});

// ==== GET CATEGORY BY ID ==== //
/**
 * @OA\Get(
 *     path="/categories/{id}",
 *     tags={"categories"},
 *     summary="Get category details by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the category",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category details"
 *     )
 * )
 */
Flight::route('GET /categories/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::USER); 
    Flight::json(Flight::categoryService()->getCategoryById($id));
});
