<?php

namespace App\Http\Controllers;

class SystemController extends Controller
{
    /**
     * Show admin dashboard view.
     *
     * @return string
     */
    public function dashboardView()
    {
        return view('system.dashboard');
    }
}
