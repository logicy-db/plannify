<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Handles authorization and authentication
 */
class AuthController extends Controller
{
    /**
     * @return string
     */
    public function login(): string
    {
        return view('auth.login');
    }

    /**
     * @return string
     */
    public function registration(): string
    {
        return view('auth.registration');
    }

    /**
     * @return string
     */
    public function forgotPassword(): string
    {
        return view('auth.forgotpassword');
    }
}
