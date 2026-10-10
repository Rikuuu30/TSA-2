<?php

namespace App\Controllers;

class AuthFlow extends BaseController
{
    public function index(): string
    {
        return view('partials/header', ['pageTitle' => 'Login and logout flow', 'activePage' => 'auth-flow'])
            . view('auth_flow')
            . view('partials/footer');
    }
}
