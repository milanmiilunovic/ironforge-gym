<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Registriramo servis
Flight::register('userAuthService', 'UserAuthService');

Flight::group('/auth', function() {

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register new user.",
     *     description="Add a new user to the database.",
     *     tags={"auth"},
     *     security={
     *         {"ApiKey": {}}
     *     },
     *     @OA\RequestBody(
     *         description="Add new user",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"password", "email", "full_name"},
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="some_password",
     *                     description="User password"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="demo@gmail.com",
     *                     description="User email"
     *                 ),
     *                 @OA\Property(
     *                     property="full_name",
     *                     type="string",
     *                     example="John Doe",
     *                     description="Full name of the user"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User has been added."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error."
     *     )
     * )
     */
    Flight::route('POST /register', function () {
        $data = Flight::request()->data->getData();

        try {
            $response = Flight::userAuthService()->registerUser($data);
            Flight::json([
                'message' => $response['message'],
                'success' => true
            ]);
        } catch (Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    });

    /**
     * @OA\Post(
     *      path="/auth/login",
     *      tags={"auth"},
     *      summary="Login to system using email and password",
     *      @OA\Response(
     *           response=200,
     *           description="User data and JWT"
     *      ),
     *      @OA\RequestBody(
     *          description="Credentials",
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", example="demo@gmail.com", description="User email address"),
     *              @OA\Property(property="password", type="string", example="some_password", description="User password")
     *          )
     *      )
     * )
     */
    Flight::route('POST /login', function () {
        $data = Flight::request()->data->getData();

        try {
            $loginResponse = Flight::userAuthService()->login($data['email'], $data['password']);
            $user = $loginResponse['user'];

            // JWT payload
            $jwt_payload = [
                'user' => $user,
                'iat' => time(),
                'exp' => time() + (60 * 60 * 24) // token valid for 1 day
            ];

            $token = JWT::encode($jwt_payload, Config::JWT_SECRET(), 'HS256');

            Flight::json([
                'message' => $loginResponse['message'],
                'user' => $user,
                'token' => $token,
                'success' => true
            ]);

        } catch (Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    });

});
?>
