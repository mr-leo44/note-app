<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;

class ResultController extends Controller
{

    public function index()
    {
        $publications = Result::orderByDesc('created_at')->paginate(15);
        return view('publications.index', compact('publications'));
    }

    public function show($id)
    {
        $publication = Result::findOrFail($id);
        return view('publications.show', compact('publication'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $publication = Result::create($validated);
        return redirect()->route('publications.index')->with('success', 'Publication créée.');
    }

    public function update(Request $request, $id)
    {
        $publication = Result::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $publication->update($validated);
        return redirect()->route('publications.index')->with('success', 'Publication modifiée.');
    }

    public function destroy($id)
    {
        $publication = Result::findOrFail($id);
        $publication->delete();
        return redirect()->route('publications.index')->with('success', 'Publication supprimée.');
    }
}
