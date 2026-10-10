<?php

namespace App\Controllers;

use App\Models\TaskModel;

class Home extends BaseController
{
    public function index(): string
    {
        $today = date('Y-m-d');
        $tasks = (new TaskModel())
            ->where('task_date', $today)
            ->where('is_archived', 0)
            ->orderBy('id', 'ASC')
            ->findAll();

        return view('partials/header', ['pageTitle' => 'Today', 'activePage' => 'home'])
            . view('home', ['tasks' => $tasks, 'today' => $today])
            . view('partials/footer');
    }
}
