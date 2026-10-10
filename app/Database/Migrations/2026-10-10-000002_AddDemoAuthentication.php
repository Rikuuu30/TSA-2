<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDemoAuthentication extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('password_hash', 'users')) {
            $this->forge->addColumn('users', [
                'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'email'],
            ]);
        }

        $this->db->table('users')
            ->where('username', 'rikuuu')
            ->update(['password_hash' => '$2y$10$4pp2nFcH.mUe2EOFP9qN2uySgbBv67yPL4udCN4GcYX.EE66bjn9G']);
    }

    public function down()
    {
        if ($this->db->fieldExists('password_hash', 'users')) {
            $this->forge->dropColumn('users', 'password_hash');
        }
    }
}
