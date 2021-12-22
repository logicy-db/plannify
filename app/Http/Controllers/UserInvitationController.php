<?php

namespace App\Http\Controllers;

use App\Mail\InviteSent;
use App\Models\Role;
use App\Models\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UserInvitationController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Mapping of the user invitation policies
        $this->authorizeResource(UserInvitation::class, 'invitation');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('invitations.index', ['invites' => UserInvitation::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roleOptions = [];

        if (Auth::user()->role_id === Role::ADMIN) {
            $allowedRoles = Role::all();
        } else {
            $allowedRoles = Role::whereNotIn('id', [Role::HUMAN_RESOURCES, Role::ADMIN]);
        }

        foreach ($allowedRoles as $role) {
            $roleOptions[$role->id] = $role->name;
        }

        return view('invitations.create', ['roleOptions' => $roleOptions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email|unique:user_invitations,email',
            'role_id' => ['required', 'exists:roles,id',
                Auth::user()->role_id === Role::ADMIN ? '' : Rule::notIn([Role::HUMAN_RESOURCES, Role::ADMIN])
            ]
        ],
        [
            'email.unique' => 'User is already registered or has a pending invite.'
        ]);

        $invitation = new UserInvitation();
        $invitation->invitation_token = md5(sprintf("%s%s", uniqid(), $request->email));
        $invitation->email = $request->email;
        $invitation->role_id = $request->role_id;
        $invitation->invited_by = Auth::id();
        $invitation->expires_at = date("Y-m-d H:i", strtotime('+24 hours'));
        $invitation->save();

        Mail::to($invitation->email)->send(new InviteSent($invitation));

        return redirect()->route('system.invitations.index')
            ->with('success', "Invitation to email {$request->email} has been sent.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserInvitation  $userInvitation
     * @return \Illuminate\Http\Response
     */
    public function show(UserInvitation $userInvitation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserInvitation  $userInvitation
     * @return \Illuminate\Http\Response
     */
    public function edit(UserInvitation $userInvitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserInvitation  $userInvitation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserInvitation $userInvitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserInvitation  $userInvitation
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserInvitation $userInvitation)
    {
        //
    }
}