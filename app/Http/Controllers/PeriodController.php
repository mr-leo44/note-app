<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::orderByDesc('name')->paginate(15);
        return view('periods.index', compact('periods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:periods,name',
        ]);
        Period::create($validated);
        return redirect()->route('periods.index')->with('success', 'Période créée avec succès.');
    }

    public function update(Request $request, Period $period)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name,' . $period->id,
        ]);
        $period->update($validated);
        return redirect()->route('periods.index')->with('success', 'Période modifiée avec succès.');
    }

    public function destroy(Period $period)
    {
        $period->delete();
        return redirect()->route('periods.index')->with('success', 'Période supprimée.');
    }
}
