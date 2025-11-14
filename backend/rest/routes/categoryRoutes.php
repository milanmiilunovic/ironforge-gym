<?php
require_once __DIR__ . '/../services/CategoryService.php';

/**
 * @OA\Post(
 *     path="/categories",
 *     tags={"categories"},
 *     summary="Create a new category",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="Yoga"),
 *             @OA\Property(property="description", type="string", example="Category for Yoga classes")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category created successfully"
 *     )
 * )
 */
Flight::route('POST /categories', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::categoryService()->createCategory($data));
});

/**
 * @OA\Put(
 *     path="/categories/{id}",
 *     tags={"categories"},
 *     summary="Update an existing category",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the category",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Updated Category"),
 *             @OA\Property(property="description", type="string", example="Updated description")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category updated successfully"
 *     )
 * )
 */
Flight::route('PUT /categories/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::categoryService()->updateCategory($id, $data));
});

/**
 * @OA\Delete(
 *     path="/categories/{id}",
 *     tags={"categories"},
 *     summary="Delete a category",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the category",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /categories/@id', function($id) {
    Flight::json(Flight::categoryService()->deleteCategory($id));
});

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
    Flight::json(Flight::categoryService()->getCategoryById($id));
});

?>
