<?php

namespace App\Mail;

use App\Models\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Role;
use App\Models\User;

class InviteSent extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $inviteSender;
    public $role;
    public $registrationLink;
    public $expirationTime;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserInvitation $invitation)
    {
        $this->role = Role::USER_ROLES[$invitation->role_id];
        $this->inviteSender = User::findOrfail($invitation->id)->getFullname();
        $this->expirationTime = $invitation->expires_at;
        $this->registrationLink = $invitation->getRegistrationLink();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Invitation to register in ' . config('app.name'))
            ->markdown('emails.invitation.sent');
    }
}
