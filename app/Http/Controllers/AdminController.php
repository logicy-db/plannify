<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Show admin dashboard view.
     *
     * @return string
     */
    public function dashboardView()
    {
        return view('admin.dashboard');
    }
}
