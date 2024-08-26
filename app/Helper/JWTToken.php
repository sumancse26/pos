<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class JWTToken
{

    static function CreateToken($userEmail, $userId): string
    {
        $key = env('JWT_KEY');

        $payload = [
            'iss' => 'token',
            'iat' => time(),
            'exp' => time() * 60 * 60,
            'userEmail' => $userEmail,
            'userId' => $userId,

        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    static function VerifyToken($token): string | object
    {
        try {
            $key = env('JWT_KEY');

            if ($token == null) {
                return 'Unauthorized';
            }
            $decodedToken = JWT::decode($token, new Key($key, 'HS256'));

            return $decodedToken;
        } catch (\Exception $ex) {

            return 'Unauthorized';
        }
    }

    static function ResetPasswordToken($userEmail): string
    {
        try {
            $key = env('JWT_KEY');

            $payload = [
                'iss' => 'token',
                'iat' => time(),
                'exp' => time() + 60 * 2,
                'userEmail' => $userEmail,
                'userId' => "0",

            ];

            return JWT::encode($payload, $key, 'HS256');
        } catch (\Exception $ex) {

            return response()->json([
                'message' => 'Unauthorized user'
            ]);
        }
    }
}
