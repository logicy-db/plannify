<?php

namespace App\Http\Controllers;

use App\Models\UserInvitation;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rule;

/**
 * Handles authorization and authentication
 */
class AuthController extends Controller
{
    /**
     * Show login page view.
     *
     * @return string
     */
    public function loginView()
    {
        return view('auth.login');
    }

    /**
     * Show registration page view.
     *
     * @return string
     */
    public function registrationView(Request $request)
    {
        $invitation = UserInvitation::whereInvitationToken(
            $request->get('invitation_token')
        )->firstOrFail();

        return view('auth.registration', ['email' => $invitation->email, 'token' => $invitation->invitation_token]);
    }

    /**
     * Show forgot password page view.
     *
     * @return string
     */
    public function forgotPasswordView()
    {
        return view('auth.forgotpassword');
    }

    /**
     * Show forgot password page view.
     *
     * @return string
     */
    public function resetPasswordView($token)
    {
        return view('auth.resetpassword', ['token' => $token]);
    }

    /**
     * Process registration request.
     *
     * @param Request $request
     */
    public function registerUser(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'invitation_token' => [
                'required',
                Rule::exists('user_invitations', 'invitation_token')
                    ->where('invitation_token', $request->invitation_token),
            ],
            'email' => [
                'required',
                Rule::exists('user_invitations', 'email')
                    ->where('invitation_token', $request->invitation_token),
                'unique:users'
            ],
            'password' => 'required|confirmed|min:8|max:20',
        ],
        [
            'invitation_token.exists' => 'Invalid invitation token. Please check the URL.',
            'email.exists' => 'Invitation is bound to the email address to which invitation was send to.',
        ]);

        $invitation = UserInvitation::whereInvitationToken($request->invitation_token)->firstOrFail();

        if ($invitation->status === UserInvitation::ACCEPTED) {
            return redirect()->back()->with('error', 'Invitation link has already been used.');
        } elseif ($invitation->status === UserInvitation::EXPIRED) {
            return redirect()->back()->with('error', 'Invitation link has already expired.');
        }

        $invitation->status = UserInvitation::ACCEPTED;
        $invitation->save();

        $user = new User;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id = $invitation->role_id;

        $invitation->status = UserInvitation::ACCEPTED;
        $invitation->save();
        $result = $user->save();

        // Redirect user to login page after successful registration
        if ($result) {
            return redirect()->route('login')->with('success', 'You have successfully registrated.');
        } else {
            return back()->with('fail', 'Registration failed, please, try again.');
        }
    }

    /**
     * Process log-in request.
     *
     * @param Request $request
     */
    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, true)){
            return redirect()->route('home')->with('success', 'Successful login');
        }

        // Displaying user fields that failed validation.
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return redirect()->route('home');
            } else {
                $error = ['password' => 'Provided password is incorrect.'];
            }
        } else {
            $error = ['email' => 'Provided email is not present in our records.'];
        }

        return back()->with('error', $error)
                     ->withInput($request->only('email'));
    }

    /**
     * Logout user and invalidate session.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logoutUser(Request $request)
    {
        Auth::logout();

        // Invalidating user session and regenerating CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Reset user password.
     *
     * @param Request $request
     */
    public function sendPasswordResetEmail(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        // TODO: Rework/add normal error handling accross project
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['status' => __($status)]);
    }

    /**
     * @param Request $request
     */
    public function resetUserPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8|max:20',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
