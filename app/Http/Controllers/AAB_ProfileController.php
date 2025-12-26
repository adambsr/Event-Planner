<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AAB_ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return redirect()->route('profile.index')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('profile.index')
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Password updated successfully.');
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'avatar' => 'required|image|max:2048', // 2MB max
        ]);

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $imageName = time() . '.' . $request->avatar->extension();
        $request->avatar->storeAs('avatars', $imageName, 'public');
        $user->update(['avatar' => 'avatars/' . $imageName]);

        return redirect()->route('profile.index')
            ->with('success', 'Avatar updated successfully.');
    }
}
