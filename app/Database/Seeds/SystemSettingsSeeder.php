<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;//thêm vào để sử dụng


class SystemSettingsSeeder extends Seeder
{
    public function run()
    {
           //Faker
        $faker = Factory::create();

        $data = [];

        //tạo data giả
        for ($i = 1; $i <= 5; $i++) {
            $key = 'setting_' . $i;
            $data[] = [
                'meta_key'   => $key,
                'meta_value' => $faker->randomElement([$faker->word, $faker->sentence, json_encode(['a' => 1])]),
                'label'      => ucfirst(str_replace('_',' ', $key)),
                'field_type' => $faker->randomElement(['text','textarea','select','switch']),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        // insert nhiều bản ghi cùng lúc
        $this->db->table('system_settings')->insertBatch($data);
    }
}
// php spark db:seed SystemSettingsSeeder sd lệnh này để tạo data fake