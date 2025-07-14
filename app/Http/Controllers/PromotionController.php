<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('department')->orderBy('name')->paginate(10);
        return view('promotions.index', compact('promotions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:promotions,name',
            'short_name' => 'required|string|max:255|unique:promotions,short_name',
            'department_id' => 'required|exists:departments,id',
        ]);
        Promotion::create($validated);
        return redirect()->route('promotions.index')->with('success', 'Promotion créée avec succès.');
    }

    public function show(Promotion $promotion)
    {
        return view('promotions.show', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:promotions,name,' . $promotion->id,
            'short_name' => 'required|string|max:255|unique:promotions,short_name,' . $promotion->id,
            'department_id' => 'required|exists:departments,id',
        ]);
        $promotion->update($validated);
        return redirect()->route('promotions.index')->with('success', 'Promotion modifiée avec succès.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('promotions.index')->with('success', 'Promotion supprimée avec succès.');
    }
}
