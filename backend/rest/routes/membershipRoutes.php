<?php
require_once __DIR__ . '/../services/MembershipService.php';

/**
 * @OA\Post(
 *     path="/memberships",
 *     tags={"memberships"},
 *     summary="Purchase a new membership",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","membership_type","start_date"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="membership_type", type="string", example="Monthly"),
 *             @OA\Property(property="start_date", type="string", example="2025-11-15")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Membership purchased successfully"
 *     )
 * )
 */
Flight::route('POST /memberships', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::membershipService()->purchaseMembership($data));
});

/**
 * @OA\Get(
 *     path="/memberships/user/{user_id}",
 *     tags={"memberships"},
 *     summary="Get all memberships for a user",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of user's memberships"
 *     )
 * )
 */
Flight::route('GET /memberships/user/@user_id', function($user_id) {
    Flight::json(Flight::membershipService()->getUserMemberships($user_id));
});

/**
 * @OA\Get(
 *     path="/memberships/user/{user_id}/active",
 *     tags={"memberships"},
 *     summary="Get active membership for a user",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns active membership"
 *     )
 * )
 */
Flight::route('GET /memberships/user/@user_id/active', function($user_id) {
    Flight::json(Flight::membershipService()->getActiveMembership($user_id));
});

/**
 * @OA\Get(
 *     path="/memberships",
 *     tags={"memberships"},
 *     summary="Get all memberships (admin)",
 *     @OA\Response(
 *         response=200,
 *         description="List of all memberships"
 *     )
 * )
 */
Flight::route('GET /memberships', function() {
    Flight::json(Flight::membershipService()->getAllMemberships());
});

/**
 * @OA\Delete(
 *     path="/memberships/{id}",
 *     tags={"memberships"},
 *     summary="Delete a membership by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the membership",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Membership deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /memberships/@id', function($id) {
    Flight::json(Flight::membershipService()->deleteMembership($id));
});
?>
