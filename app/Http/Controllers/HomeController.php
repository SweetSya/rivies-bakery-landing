<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends RiviesAPIController
{
    public function __construct()
    {
        parent::__construct();
}
    public function view()
    {
        $data = [];
        $response = $this->apiGet('home', []);
        $data = $response->json();
        return Inertia::render('Home', [
            'serverData' => $data
        ]);
    }
}
