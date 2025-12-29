<?php

namespace App\Http\Controllers;

use App\Http\Requests\AAB_LoginRequest;
use App\Http\Requests\AAB_RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        User::create($validated);

        return redirect()->route('login')
            ->with('success', 'Registration succeeded ! You can now login.');
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
}
