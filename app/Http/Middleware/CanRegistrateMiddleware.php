<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\UserInvitation;

class CanRegistrateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->has('invitation_token')) {
            return redirect('login');
        }

        $invitation_token = $request->get('invitation_token');

        try {
            /** @var UserInvitation $invitation */
            $invitation = UserInvitation::whereInvitationToken($invitation_token)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('login')->with('error', 'Invalid invitation token. Please check your URL.');
        }

        if ($invitation->status === UserInvitation::ACCEPTED) {
            return redirect('login')->with('error', 'Invitation link has already been used.');
        } elseif ($invitation->status === UserInvitation::EXPIRED) {
            return redirect('login')->with('error', 'Invitation link has already expired.');
        }

        return $next($request);
    }
}
