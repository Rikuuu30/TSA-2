<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTaskArchiveFlag extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('is_archived', 'tasks')) {
            $this->forge->addColumn('tasks', [
                'is_archived' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => false,
                    'after'      => 'status',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('is_archived', 'tasks')) {
            $this->forge->dropColumn('tasks', 'is_archived');
        }
    }
}
