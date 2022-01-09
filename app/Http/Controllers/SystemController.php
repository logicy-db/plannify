<?php

namespace App\Http\Controllers;

class SystemController extends Controller
{
    /**
     * Show system dashboard view.
     *
     * @return string
     */
    public function dashboardView()
    {
        return view('system.dashboard');
    }
}
