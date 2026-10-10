<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $today = new \DateTimeImmutable('today');
        $createdAt = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        $dates = [
            'yesterday' => $today->modify('-1 day')->format('Y-m-d'),
            'today' => $today->format('Y-m-d'),
            'tomorrow' => $today->modify('+1 day')->format('Y-m-d'),
        ];

        $this->db->table('tasks')->truncate();
        $this->db->table('users')->truncate();

        $this->db->table('tasks')->insertBatch([
            ['title' => 'Prepare cycling clothes and check the bike so the early ride is ready.', 'status' => 'pending', 'task_date' => $dates['yesterday'], 'created_at' => $createdAt],
            ['title' => 'Wake up at 5:00 a.m. and get dressed for an early, productive start to the day.', 'status' => 'pending', 'task_date' => $dates['today'], 'created_at' => $createdAt],
            ['title' => 'Go for a morning bike ride to get some exercise and enjoy the fresh air.', 'status' => 'pending', 'task_date' => $dates['today'], 'created_at' => $createdAt],
            ['title' => 'Take a refreshing shower after cycling and change into clean, comfortable clothes.', 'status' => 'pending', 'task_date' => $dates['today'], 'created_at' => $createdAt],
            ['title' => 'Do the laundry, then fold and put away the clothes once everything is dry.', 'status' => 'pending', 'task_date' => $dates['today'], 'created_at' => $createdAt],
            ['title' => 'Eat a balanced lunch and take a short break to recharge for the afternoon.', 'status' => 'pending', 'task_date' => $dates['today'], 'created_at' => $createdAt],
            ['title' => 'Head to Anytime Fitness for a workout, starting with a warm-up and finishing with a cooldown.', 'status' => 'pending', 'task_date' => $dates['today'], 'created_at' => $createdAt],
            ['title' => 'Take another shower after the workout and change into fresh clothes.', 'status' => 'pending', 'task_date' => $dates['today'], 'created_at' => $createdAt],
            ['title' => 'Spend some time playing and relaxing after completing the day’s responsibilities.', 'status' => 'pending', 'task_date' => $dates['today'], 'created_at' => $createdAt],
            ['title' => 'Set out clean clothes and review the next day’s routine before going to bed.', 'status' => 'pending', 'task_date' => $dates['tomorrow'], 'created_at' => $createdAt],
        ]);

        $this->db->table('users')->insert([
            'username' => 'rikuuu',
            'full_name' => 'Rikuuu',
            'email' => 'rikuuu@example.com',
            'password_hash' => '$2y$10$4pp2nFcH.mUe2EOFP9qN2uySgbBv67yPL4udCN4GcYX.EE66bjn9G',
            'created_at' => $createdAt,
        ]);
    }
}
