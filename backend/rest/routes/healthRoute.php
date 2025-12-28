<?php


/**
 * @OA\Get(
 *     path="/health",
 *     tags={"health"},
 *     summary="Get the API status",
 *     @OA\Response(
 *         response=200,
 *         description="API Working!"
 *     )
 * )
 */

Flight::route('GET /health', function (){
    echo "OK";
});