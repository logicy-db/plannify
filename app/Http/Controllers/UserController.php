<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Mapping of the user policies
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index',  ['users' => User::all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(User $user)
    {
        $roleOptions = [];
        if (Auth::user()->role_id === Role::ADMIN) {
            // Fetching all possible user roles
            foreach (Role::all() as $role) {
                $roleOptions[$role->id] = $role->name;
            }
        }

        return view(
            'users.show',
            ['user' => $user, 'roleOptions' => $roleOptions]
        );
    }

    /**
     *
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        if (Auth::id() === $user->id) {
            // If account owner updates the data
            $request->validate([
                'email' => sprintf('required|email|max:255|unique:users,email,%s', $user->id),
                'current_password' => 'required|current_password',
                'new_password' => 'nullable|confirmed|min:8',
            ]);

            if ($request->new_password){
                $user->password = Hash::make($request->new_password);
            }
            $user->email = $request->email;
        } elseif (Auth::user()->role_id === Role::ADMIN) {
            $request->validate([
                'email' => sprintf('required|email|unique:users,email,%s', $user->id),
                'role_id' => 'required|exists:roles,id'
            ]);

            $user->email = $request->email;
            $user->role_id = $request->role_id;
        }

        $user->save();

        return back()->with('success', sprintf('Account data were updated'));
    }

    public function changeUserStatus(User $user) {
        $this->authorize('changeUserStatus', $user);

        if ($user->active) {
            $user->active = User::STATUS_DISABLED;
            $msg = 'User has been disabled.';
        } else {
            $user->active = User::STATUS_ACTIVE;
            $msg = 'User has been activated.';
        }
        $user->save();

        return back()->with('success', $msg);
    }
}
