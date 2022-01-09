<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsUserActiveMiddleware
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
        if (!auth()->check() && $request->email) {
            // Avoiding using standard validation, since middleware will remove some messages
            // returned by AuthController::loginUser
            $user = User::whereEmail($request->email)->first();

            if ($user && $user->active === User::STATUS_DISABLED) {
                return abort(
                    '403',
                    sprintf("Your account was disabled. For more details, contact us using following email: %s", env('MAIL_FROM_ADDRESS'))
                );
            }
        }

        if (auth()->check() && auth()->user()->active === User::STATUS_DISABLED) {
            Auth::logout();

            // Invalidating user session and regenerating CSRF token
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Your account has been disabled.');
        }

        return $next($request);
    }
}
