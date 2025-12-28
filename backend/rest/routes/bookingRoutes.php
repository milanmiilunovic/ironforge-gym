<?php
require_once __DIR__ . '/../services/BookingService.php';
require_once __DIR__ . '/../../data/roles.php';

/**
 * @OA\Get(
 *     path="/users/{id}/bookings",
 *     tags={"bookings"},
 *     summary="Get all bookings for a user",
 *      @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="A list of all bookings for a user"
 *     )
 * )
 */
Flight::route('GET /users/@id/bookings', function($id) {
    Flight::json(Flight::bookingService()->getUserBookings($id));
});

/**
 * @OA\Get(
 *     path="/bookings/{id}",
 *     tags={"bookings"},
 *     summary="Get a booking by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the booking",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="The booking"
 *     )
 * )
 */
Flight::route('GET /bookings/@id', function($id) {
    Flight::json(Flight::bookingService()->getBookingById($id));
});

/**
 * @OA\Post(
 *     path="/bookings",
 *     tags={"bookings"},
 *     summary="Create a new booking",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="user_id",
 *                     type="integer",
 *                     example=1
 *                 ),
 *                 @OA\Property(
 *                     property="class_id",
 *                     type="integer",
 *                     example=1
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Booking created successfully"
 *     )
 * )
 */
Flight::route('POST /bookings', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::bookingService()->createBooking($data));
});

/**
 * @OA\Delete(
 *     path="/bookings/{id}",
 *     tags={"bookings"},
 *     summary="Cancel a booking",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the booking to cancel",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Booking cancelled successfully"
 *     )
 * )
 */
Flight::route('DELETE /bookings/@id', function($id) {


    Flight::json(Flight::bookingService()->cancelBooking($id));
});

/**
 * @OA\Get(
 *     path="/users/{id}/bookings/active",
 *     tags={"bookings"},
 *     summary="Get all active bookings for a user",
 *      @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="A list of all active bookings for a user"
 *     )
 * )
 */
Flight::route('GET /users/@id/bookings/active', function($id) {
    Flight::json(Flight::bookingService()->getActiveBookings($id));
});
