<?php

namespace App\Http\Controllers;

use App\Models\AAB_Event;
use App\Models\AAB_Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AAB_RegistrationController extends Controller
{
    /**
     * Register user to an event
     */
    public function store(Request $request, AAB_Event $event)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to register for an event.');
        }

        // Check if event is full
        if ($event->isFull()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Sorry, this event is full.');
        }

        // Check if user is already registered
        $existingRegistration = AAB_Registration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($existingRegistration) {
            return redirect()->route('events.show', $event)
                ->with('error', 'You are already registered for this event.');
        }

        // Create registration
        AAB_Registration::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
        ]);

        return redirect()->route('events.show', $event)
            ->with('success', 'Registration succeeded !');
    }

    /**
     * Unregister user from an event
     */
    public function destroy(AAB_Event $event)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $registration = AAB_Registration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($registration) {
            $registration->delete();
            return redirect()->route('events.show', $event)
                ->with('success', 'Unregistration succeeded.');
        }

        return redirect()->route('events.show', $event)
            ->with('error', 'You are not registered for this event.');
    }

    /**
     * Show user's registrations
     */
    public function myRegistrations()
    {
        $registrations = AAB_Registration::with('event.category')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('registrations.my-registrations', compact('registrations'));
    }

    /**
     * Show all registrations (Admin only)
     */
    public function index()
    {
        $registrations = AAB_Registration::with('user', 'event.category')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('registrations.index', compact('registrations'));
    }
}
