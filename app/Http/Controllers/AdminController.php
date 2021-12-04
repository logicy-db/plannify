<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Show user list page.
     *
     * @return string
     */
    public function userListView()
    {
        return view('admin.user.index',  ['users' => User::all()]);
    }

    /**
     * Show user view page.
     *
     * @return string
     */
    public function userView(int $id)
    {
        // TODO: refactor the code
        // Fetching user roles
        $roleOptions = [];
        foreach (Role::all() as $role) {
            $roleOptions[$role->id] = $role->name;
        }

        return view(
            'admin.user.view',
            ['id' => $id, 'user' => User::findOrFail($id), 'roleOptions' => $roleOptions]
        );
    }

    /**
     * Show registration page view.
     *
     * @return string
     */
    public function updateUser(int $id, Request $request)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id, 'id')],
            'firstname' => 'required',
            'lastname' => 'required',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->role_id = $request->role_id;

        $user->save();

        return back()->with('success', sprintf('User %s was updated', $user->getFullname()));
    }

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
