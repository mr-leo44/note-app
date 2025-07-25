<?php

namespace App\Http\Controllers;

use App\Models\Jury;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Enums\StudentPromotionStatus;

class PromotionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userRole = $user->account->accountable_type;
        if ($userRole === Jury::class) {
            $jury_id = $user->account->accountable_id;
            $juryPromotions = DB::table('jury_promotion')
                ->where('jury_id', Auth::user()->account->accountable_id)
                ->pluck('promotion_id');
            $promotions = Promotion::whereIn('id', $juryPromotions)->paginate();
        } else {
            $promotions = Promotion::with('department')->orderBy('name')->paginate(10);
        }
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
        return redirect()->back()->with('success', 'Promotion créée avec succès.');
    }

    public function show(Promotion $promotion)
    {
        $students = $promotion->students()->paginate();
        return view('promotions.show', compact(['promotion', 'students']));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:promotions,name,' . $promotion->id,
            'short_name' => 'required|string|max:255|unique:promotions,short_name,' . $promotion->id,
            'department_id' => 'required|exists:departments,id',
        ]);
        $promotion->update($validated);
        return redirect()->back()->with('success', 'Promotion modifiée avec succès.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->back()->with('success', 'Promotion supprimée avec succès.');
    }
}
