<?php

namespace App\Http\Controllers;

use App\Http\Requests\AAB_EventRequest;
use App\Models\AAB_Event;
use App\Models\AAB_Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AAB_EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Public view - only active events
        $query = AAB_Event::with('category')->where('status', 'active');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by weekday
        if ($request->filled('weekday')) {
            $weekday = ucfirst(strtolower($request->weekday)); // Capitalize first letter (Monday, Tuesday, etc.)
            $query->whereRaw('DAYNAME(start_date) = ?', [$weekday]);
        }

        $events = $query->orderBy('start_date', 'asc')->paginate(12);
        $categories = AAB_Category::all();

        return view('events.index', compact('events', 'categories'));
    }

    /**
     * Display admin list of all events.
     */
    public function adminList(Request $request)
    {
        $query = AAB_Event::with('category');

        // Search functionality
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->orderBy('start_date', 'desc')->paginate(20);

        return view('events.list', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('edit events');
        $categories = AAB_Category::all();
        $event = new AAB_Event();
        return view('events.create', compact('categories', 'event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AAB_EventRequest $request)
    {
        $this->authorize('edit events');
        $validated = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('events', $imageName, 'public');
            $validated['image'] = 'events/' . $imageName;
        }

        // Set created_by to current user
        $validated['created_by'] = Auth::id();
        
        // Set is_free based on price
        if (!isset($validated['is_free'])) {
            $validated['is_free'] = ($validated['price'] == 0 || empty($validated['price']));
        }

        AAB_Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AAB_Event $event)
    {
        $event->load('category', 'creator', 'registrations.user');
        $isRegistered = Auth::check() && $event->registrations()
            ->where('user_id', Auth::id())
            ->exists();
        
        return view('events.show', compact('event', 'isRegistered'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AAB_Event $event)
    {
        $this->authorize('edit events');
        $categories = AAB_Category::all();
        return view('events.edit', compact('event', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AAB_EventRequest $request, AAB_Event $event)
    {
        $this->authorize('edit events');
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            
            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('events', $imageName, 'public');
            $validated['image'] = 'events/' . $imageName;
        }

        // Set is_free based on price
        if (!isset($validated['is_free'])) {
            $validated['is_free'] = ($validated['price'] == 0 || empty($validated['price']));
        }

        $event->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AAB_Event $event)
    {
        $this->authorize('delete events');
        // Archive the event instead of deleting
        $event->update(['status' => 'archived']);

        return redirect()->route('events.list')
            ->with('success', 'Event archived successfully.');
    }
}
