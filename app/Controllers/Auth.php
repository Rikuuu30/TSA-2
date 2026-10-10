<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    public function login(): string|RedirectResponse
    {
        if (session()->has('user_id')) {
            return redirect()->to(site_url('/'));
        }

        return view('partials/header', ['pageTitle' => 'Sign in', 'activePage' => 'login'])
            . view('login')
            . view('partials/footer');
    }

    public function attempt(): RedirectResponse|string
    {
        $usernameInput = $this->request->getPost('username');
        $passwordInput = $this->request->getPost('password');
        $username = is_string($usernameInput) ? trim($usernameInput) : '';
        $password = is_string($passwordInput) ? $passwordInput : '';

        if ($username === '' || $password === '') {
            return redirect()->back()->withInput()->with('error', 'Enter both your username and password.');
        }

        $limitKey = 'taskstoday-login-' . hash('sha256', $this->request->getIPAddress());
        if (!service('throttler')->check($limitKey, 5, 60)) {
            return redirect()->back()->with('error', 'Too many sign-in attempts. Wait a minute and try again.');
        }

        $user = (new UserModel())->where('username', $username)->first();
        if ($user === null || !is_string($user['password_hash'] ?? null) || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()->with('error', 'The username or password is incorrect.');
        }

        session()->regenerate(true);
        session()->set([
            'user_id' => (int) $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'],
        ]);

        return redirect()->to(site_url('/'))->with('success', 'Welcome back, ' . $user['full_name'] . '.');
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();

        return redirect()->to(site_url('login'))->with('success', 'You have been signed out.');
    }
}
