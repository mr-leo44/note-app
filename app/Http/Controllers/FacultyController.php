<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faculties = Faculty::orderBy('name')->paginate(10);
        return view('faculties.index', compact('faculties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name',
            'short_name' => 'required|string|max:50|unique:faculties,short_name',
        ]);
        Faculty::create($validated);
        return redirect()->back()->with('success', 'Section créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Faculty $faculty)
    {
        return view('faculties.show', compact('faculty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name,' . $faculty->id,
            'short_name' => 'required|string|max:50|unique:faculties,short_name,' . $faculty->id,
        ]);
        $faculty->update($validated);
        return redirect()->back()->with('success', 'Section modifiée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faculty $faculty)
    {
        $faculty->delete();
        return redirect()->back()->with('success', 'Section supprimée avec succès.');
    }
}
