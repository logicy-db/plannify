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
     * User invitation listing view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('invitations.index', ['invites' => UserInvitation::all()]);
    }

    /**
     * Form to create a new user invitation.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $roleOptions = [];

        if (Auth::user()->role_id === Role::ADMIN) {
            $allowedRoles = Role::all();
        } elseif (Auth::user()->role_id === Role::HUMAN_RESOURCES) {
            $allowedRoles = Role::whereNotIn('id', [Role::HUMAN_RESOURCES, Role::ADMIN]);
        }

        foreach ($allowedRoles as $role) {
            $roleOptions[$role->id] = $role->name;
        }

        return view('invitations.create', ['roleOptions' => $roleOptions]);
    }

    /**
     * Storing of newly created invitation.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|unique:users,email|unique:user_invitations,email',
            'role_id' => ['required', 'exists:roles,id',
                Auth::user()->role_id === Role::ADMIN ? '' : Rule::notIn([Role::HUMAN_RESOURCES, Role::ADMIN])
            ]
        ],
        [
            'email.unique' => 'User is already registered or has an invite.'
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
     * Resend user invitation.
     *
     * @param UserInvitation $userInvitation
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function resendInvite(UserInvitation $userInvitation)
    {
        $this->authorize('update', $userInvitation);

        $userInvitation->expires_at = date(
            'Y-m-d H:i:s',
            strtotime('+24 hours', strtotime(now()))
        );
        $userInvitation->status = UserInvitation::PENDING;
        $userInvitation->save();

        Mail::to($userInvitation->email)->send(new InviteSent($userInvitation));

        return redirect()->route('system.invitations.index')
            ->with('success', "Invitation to email {$userInvitation->email} has been resent.");
    }
}
