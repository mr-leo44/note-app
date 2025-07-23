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
            'current' => 'nullable|boolean',
        ]);
        $isCurrent = $request->has('current');
        if ($isCurrent) {
            // Mettre toutes les périodes à false
            Period::query()->update(['current' => false]);
        }
        $period = Period::create([
            'name' => $validated['name'],
            'current' => $isCurrent,
        ]);
        // Sécurité : s'assurer qu'une seule période est à true
        if ($isCurrent) {
            Period::where('id', '!=', $period->id)->update(['current' => false]);
        }
        return redirect()->back()->with('success', 'Période créée avec succès.');
    }

    public function update(Request $request, Period $period)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:periods,name,' . $period->id,
            'current' => 'nullable|boolean',
        ]);
        $isCurrent = $request->has('current');
        if ($isCurrent) {
            Period::query()->update(['current' => false]);
        }
        $period->update([
            'name' => $validated['name'],
            'current' => $isCurrent,
        ]);
        // Sécurité : s'assurer qu'une seule période est à true
        if ($isCurrent) {
            Period::where('id', '!=', $period->id)->update(['current' => false]);
        }
        return redirect()->back()->with('success', 'Période modifiée avec succès.');
    }

    public function destroy(Period $period)
    {
        $period->delete();
        return redirect()->back()->with('success', 'Période supprimée.');
    }
}
