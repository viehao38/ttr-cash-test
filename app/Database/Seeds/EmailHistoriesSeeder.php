<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;//thêm vào để sử dụng

class EmailHistoriesSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 5; $i++) { 
            $sentAt = $faker->optional()->dateTimeThisYear();
            $sentAtFormatted = $sentAt ? $sentAt->format('Y-m-d H:i:s') : null;

            $this->db->table('emails_histories')->insert([
                'code'          => strtoupper($faker->bothify('EM-####')),
                'recipient'     => $faker->email,
                'cc'            => $faker->optional()->email,
                'bcc'           => $faker->optional()->email,
                'subject'       => $faker->sentence(6),
                'body'          => $faker->paragraph(3),
                'error_message' => $faker->optional()->sentence(),
                'status'        => $faker->numberBetween(0, 1),
                'sent_at'       => $sentAtFormatted,
                'resent_times'  => $faker->numberBetween(0, 5),
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
                'deleted_at'    => null,
            ]);
        }
    }
}
//php spark make:seeder EmailSeeder
// php spark db:seed EmailHistoriesSeeder
