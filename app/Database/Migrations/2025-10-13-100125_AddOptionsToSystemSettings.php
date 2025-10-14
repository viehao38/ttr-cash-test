<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOptionsToSystemSettings extends Migration
{
    public function up()
    {
           $fields = [
            'options' => [
                'type' => 'TEXT',
                'null' => true, 
            ],
        ];

        $this->forge->addColumn('system_settings', $fields);
    }

    public function down()
    {
        //
    }
}
