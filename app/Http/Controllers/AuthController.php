<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

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
    public function registrationView()
    {
        return view('auth.registration');
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
     * Show new user to the website view.
     *
     * @return string
     */
    public function invitationView()
    {
        return view('auth.invitation');
    }

    /**
     * Process registration request.
     *
     * @param Request $request
     */
    public function registerUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8|max:20',
            'firstname' => 'required',
            'middlename' => 'nullable',
            'lastname' => 'required',
        ]);

        $user = new User;
        $user->email = $request->email;
        $user->first_name = $request->firstname;
        $user->middle_name = $request->middlename;
        $user->last_name = $request->lastname;
        $user->password = Hash::make($request->password);

        $result = $user->save();

        if ($result) {
            // Log in user after successful registration
            if (Auth::attempt($request->only('email', 'password'), true)) {
                return redirect()
                    ->route('home')
                    ->with('Welcome! You have been successfully registered!');
            }
            return back()->with('fail', 'Authentication after registration failed, please, try to log-in manually.');
        }

        return back()->with('fail', 'Registration failed, please, try again.');
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
            return redirect()->route('home');
        }

        // Displaying user what fields failed validation.
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return redirect()->route('home');
            }
            return back()->withErrors([
                'password' => 'The provided password is incorrect.',
            ]);
        }
        return back()->withErrors([
            'email' => 'The provided email is not present in our records.',
        ]);
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

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __('ALL GOOD')])
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
