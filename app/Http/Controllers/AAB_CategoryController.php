<?php

namespace App\Http\Controllers;

use App\Http\Requests\AAB_CategoryRequest;
use App\Models\AAB_Category;

class AAB_CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = AAB_Category::withCount('events')->orderBy('name')->get();
        return view('categories.list', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AAB_CategoryRequest $request)
    {
        $this->authorize('create categories');
        AAB_Category::create($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AAB_Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AAB_CategoryRequest $request, AAB_Category $category)
    {
        $this->authorize('edit categories');
        $category->update($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AAB_Category $category)
    {
        $this->authorize('delete categories');
        // Check if category has events
        if ($category->events()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete this category because it contains events.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
