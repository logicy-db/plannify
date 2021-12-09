<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index',  ['users' => User::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     */
    public function show($id)
    {
        // Fetching the possible user roles
        $roleOptions = [];
        foreach (Role::all() as $role) {
            $roleOptions[$role->id] = $role->name;
        }

        return view(
            'users.show',
            ['id' => $id, 'user' => User::findOrFail($id), 'roleOptions' => $roleOptions]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     *
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request)
    {
        // TODO: implement possibility to change password
        $user = User::findOrFail($id);

        $request->validate([
            'email' => sprintf('required|email|unique:users,email,%s', $user->id),
            'role_id' => 'required|exists:roles,id'
        ]);

        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->save();

        return back()->with('success', sprintf('User %s was updated', $user->getFullname()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
