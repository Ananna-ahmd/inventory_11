<?php
namespace App\Helper;
use Exception;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class JWTToken{
    public static function CreateToken($user_id, $user_email):string{
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token',
            'iat' => time(),
            'exp' => time() + 60 * 60*24 * 30,
            'user_id' => $user_id,
            'user_email' => $user_email,

        ];
        return JWT::encode($payload, $key, alg:'HS256');

}
public static function CreateTokenForPasswordReset($user_email): string {
    $key = env('JWT_KEY');
    $payload = [
        'iss' => 'laravel-token', // Different issuer
        'iat' => time(),
        'exp' => time() + 60 * 15, // 15 minutes
        'user_email' => $user_email, // Focus on email for password reset
        'user_id' => '0'
    ];
    return JWT::encode($payload, $key, 'HS256');
}
public static function VerifyToken($token): string|object {
    try {
        if($token == null){
            return"unauthorized";
        }
        else{
        $key = env('JWT_KEY');
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        return $decoded;}
    } catch (Exception $e) {
        // Handle specific exceptions if needed (e.g., ExpiredException)
        return "unauthorized"; // Invalid/expired token
    }
}

}
