<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmailHistoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'code'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'recipient'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'cc'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'bcc'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'subject'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'body'          => ['type' => 'TEXT', 'null' => false],
            'error_message' => ['type' => 'TEXT', 'null' => true],
            'status'        => ['type' => 'TINYINT', 'constraint' => 1, 'null' => false],
            'sent_at'       => ['type' => 'DATETIME', 'null' => true],
            'resent_times'  => ['type' => 'INT', 'constraint' => 10, 'null' => false, 'default' => 0],
            'deleted_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],

        ]);

        $this->forge->addKey('id', true); // primary key
        $this->forge->createTable('emails_histories', true);
    }

    public function down()
    {
        $this->forge->dropTable('emails_histories', true);
    }
}

//sau khi setup migratioin email chay lenh php spark migrate de tao bang
