<?php

namespace App\Controllers\Login;

use App\Controllers\BaseController;
use App\Services\JwtAuthService;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\UsersModel;

class LoginController extends ResourceController
{
    protected $jwtService;

    protected $user;
    protected $request;

    public function __construct()
    {
        $this->jwtService = new JwtAuthService();
        $this->user = new UsersModel();
    }

    public function index()
    {
        //
    }

    // public function getToken()
    // {
    //     // Đây là dữ liệu user mẫu
    //     // $userData = [
    //     //     'id' => 1,
    //     //     'email' => 'test@example.com',
    //     //     'role_id' => 1 // admin
    //     // ];

    //     $userData = [
    //         'id' => 2,
    //         'email' => 'test@example.com',
    //         'role_id' => 0 // admin
    //     ];

    //     $token = $this->jwtService->generateToken($userData, 3600);//tạo token

    //     return $this->respond([
    //         'status' => true,
    //         'token' => $token
    //     ]);
    // }
    public function getToken()
    {
        $request = $this->request->getJSON(); // lấy dữ liệu JSON từ POST
        $email = $request->email ?? null;
        $password = $request->password ?? null;

        if (!$email || !$password) {
            return $this->respond([
                'status' => false,
                'message' => 'Email và mật khẩu là bắt buộc.'
            ], 400);
        }

        // // Tìm user theo email
        $user = $this->user->where('email', $email)->first();

        if (!$user) {
            return $this->respond([
                'status' => false,
                'message' => 'Email không tồn tại.'
            ], 404);
        }



        // Kiểm tra mật khẩu (nếu lưu hashed)
        if (!password_verify($password, $user['password'])) {
            return $this->respond([
                'status' => false,
                'message' => 'Mật khẩu không đúng.'
            ], 401);
        }

        // Chuẩn bị dữ liệu payload cho JWT
        $userData = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role_id' => $user['role_id']
        ];

        // Tạo token JWT (hết hạn 1 giờ)
        $token = $this->jwtService->generateToken($userData, 3600);

        return $this->respond([
            'status' => true,
            'token' => $token
        ]);
    }
}
