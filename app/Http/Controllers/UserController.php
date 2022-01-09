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
     * User listing view.
     */
    public function index()
    {
        return view('users.index',  ['users' => User::all()]);
    }

    /**
     * Display the specified user.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\View
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
     * Updates the specified user.
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
            ],[
                'email.unique' => 'User with this email is already registered or has an invite.'
            ]);

            if ($request->new_password){
                $user->password = Hash::make($request->new_password);
            }
            $user->email = $request->email;
        } elseif (Auth::user()->role_id === Role::ADMIN) {
            // If admin updates other user data
            $request->validate([
                'email' => sprintf('required|email|max:255|unique:users,email,%s', $user->id),
                'role_id' => 'required|exists:roles,id'
            ],[
                'email.unique' => 'User with this email is already registered or has an invite.'
            ]);

            $user->email = $request->email;
            $user->role_id = $request->role_id;
        }

        $user->save();

        return back()->with('success', sprintf('Account data were updated'));
    }

    /**
     * Toggle user status.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeUserStatus(User $user) {
        $this->authorize('changeUserStatus', $user);

        if ($user->active === User::STATUS_ACTIVE) {
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
