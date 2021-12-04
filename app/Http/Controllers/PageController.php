<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Handles page routing
 */
class PageController extends Controller
{
    /**
     * Homepage
     */
    public function home() {
        return view('page.home');
    }
}
