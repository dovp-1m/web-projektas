<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'username'     => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'password'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'first_name'   => ['type' => 'VARCHAR', 'constraint' => 50],
            'last_name'    => ['type' => 'VARCHAR', 'constraint' => 50],
            'bio'          => ['type' => 'TEXT', 'null' => true],
            'avatar'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'role'         => ['type' => 'ENUM', 'constraint' => ['user', 'admin'], 'default' => 'user'],
            'is_active'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}