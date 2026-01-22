<?php

namespace App\Http\Controllers;

use App\Http\Requests\AAB_LoginRequest;
use App\Http\Requests\AAB_RegisterRequest;
use App\Http\Requests\AAB_ForgotPasswordRequest;
use App\Http\Requests\AAB_ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AAB_AuthController extends Controller
{
    /**
     * Show login form
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function toLogin(AAB_LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect based on role
            if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager')) {
                return redirect()->intended(route('events.list'));
            }
            
            return redirect()->intended(route('home'));
        }

        return redirect()->route('login')
            ->withErrors(['email' => 'Invalid email or password'])
            ->withInput($request->only('email'));
    }

    /**
     * Show register form
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Handle register request
     */
    public function toRegister(AAB_RegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'user';

        $user = User::create($validated);

        // Fire the Registered event for email verification
        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Registration succeeded! Please verify your email.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /*
    |--------------------------------------------------------------------------
    | Password Reset Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle sending password reset link
     */
    public function sendResetLinkEmail(AAB_ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword(AAB_ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
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

    /*
    |--------------------------------------------------------------------------
    | Email Verification Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Show email verification notice
     */
    public function showVerificationNotice(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('home'))
            : view('auth.verify-email');
    }

    /**
     * Handle email verification
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect()->intended(route('home'))->with('status', 'Email verified successfully!');
    }

    /**
     * Resend verification email
     */
    public function resendVerificationEmail(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Verification link sent!');
    }
}
