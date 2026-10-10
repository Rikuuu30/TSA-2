<?php

namespace App\Controllers;

class About extends BaseController
{
    public function index(): string
    {
        return view('partials/header', ['pageTitle' => 'About', 'activePage' => 'about'])
            . view('about')
            . view('partials/footer');
    }
}
