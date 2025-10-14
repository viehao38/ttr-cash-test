<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuthService
{
    protected $secretKey;

    public function __construct()
    {
        // Khóa bí mật dùng để tạo và kiểm tra JWT
        $this->secretKey = getenv('JWT_SECRET') ?: 'my_secret_key';
    }

    /**
     * Xác thực JWT từ header Authorization
     * Trả về mảng ['status' => true/false, 'user_info' => ...]
     */
    public function authenticateUser(): array
    {
        //dùng để lấy tất cả các header HTTP
        $headers = getallheaders();
        print_r($headers['Authorization']."\n");
        

        if (!isset($headers['Authorization'])) {
            return [
                'status' => false,
                'message' => 'No Authorization header'
            ];
        }

        // Lấy token (dạng "Bearer <token>")
        $token = trim(str_replace('Bearer', '', $headers['Authorization']));

        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            return [
                'status' => true,
                'user_info' => (array) $decoded
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => 'Invalid token'
            ];
        }
    }

    /**
     * Tạo JWT từ dữ liệu user
     */
    public function generateToken(array $userData, int $expireInSeconds = 3600): string
    {
        $payload = [
            'iat' => time(),
            'exp' => time() + $expireInSeconds,
            'data' => $userData
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }
}
