<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
        parent::__construct();
    }

    // Login
    public function login()
    {
        return view('back.auth.login');
    }
}
