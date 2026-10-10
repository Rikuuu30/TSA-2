<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    public function index(): string
    {
        $users = new UserModel();
        $userId = session()->get('user_id');
        $user = $userId !== null
            ? $users->find((int) $userId)
            : $users->orderBy('id', 'ASC')->first();

        return view('partials/header', ['pageTitle' => 'Profile', 'activePage' => 'profile'])
            . view('profile', ['user' => $user])
            . view('partials/footer');
    }
}
