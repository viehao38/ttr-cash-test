<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;//thêm vào để sử dụng

class UsersSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        $data = [];

        // Tạo 3 user giả
        for ($i = 1; $i <= 3; $i++) {
            $name = $faker->name;
            $email = $faker->unique()->safeEmail;
            $password = password_hash('123456', PASSWORD_DEFAULT); // mật khẩu mặc định là 123456

            $data[] = [
                'name'       => $name,
                'email'      => $email,
                'role_id'    => $faker->numberBetween(0, 1), // ví dụ 0 = user, 1 = admin
                'password'   => $password,
            ];
        }

        // Chèn nhiều bản ghi cùng lúc
        $this->db->table('users')->insertBatch($data);
    }
}
