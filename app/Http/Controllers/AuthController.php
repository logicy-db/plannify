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
     * Process registration request.
     *
     * @param Request $request
     */
    public function registerUser(Request $request)
    {
        // TODO: create invitation for the user to register.
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8|max:20',
        ]);

        $user = new User;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $result = $user->save();

        // Log in user after successful registration
        if ($result) {
            $this->loginUser($request);
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

        return back()->withErrors($error)
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
