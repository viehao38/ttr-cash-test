<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateEmailsHistoriesTable extends Migration
{
    public function up()
    {
        $fields = [
            'resent_times' => [
                'type'       => 'INT',
                'constraint' => 2,
                'null'       => false,
                'default'    => 0
            ],
        ];

        $this->forge->modifyColumn('emails_histories', $fields);
        // để sửa đổi cấu trúc cột đã tồn tại trong bảng mà không cần xóa bảng hay tạo lại.
    }

    public function down()
    {
        $fields = [
            'resent_times' => [
                'type'       => 'INT',
                'constraint' => 10,
                'null'       => false,
                'default'    => 0
            ],
        ];

        $this->forge->modifyColumn('emails_histories', $fields);
    }
}

//php spark make:migration UpdateEmailsHistoriesTable
// de update UpdateEmailsHistoriesTable
//php spark migrate
