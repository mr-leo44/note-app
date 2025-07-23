<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;
use App\Models\ResultSession;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = ResultSession::orderByDesc('name')->paginate(15);
        return view('sessions.index', compact('sessions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'current' => 'nullable|boolean',
        ]);

        $currentPeriod = Period::where('current', true)->first();
        if (ResultSession::where('name', $request['name'])->where('period_id', $currentPeriod->id)->exists()) {
            return redirect()->back()->with('warning', 'Cette session existe déjà.');
        } else {
            $isCurrent = $request->has('current');
            if ($isCurrent) {
                // Mettre toutes les périodes à false
                ResultSession::query()->update(['current' => false]);
            }
            $session = ResultSession::create([
                'name' => $validated['name'],
                'period_id' => $currentPeriod->id,
                'current' => $isCurrent,
            ]);
            // Sécurité : s'assurer qu'une seule session est à true
            if ($isCurrent) {
                ResultSession::where('id', '!=', $session->id)->update(['current' => false]);
            }
            return redirect()->back()->with('success', 'Session créée avec succès.');
        }
    }

    public function update(Request $request, ResultSession $session)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'current' => 'nullable|boolean',
        ]);

        $currentPeriod = Period::where('current', true)->first();
        $isCurrent = $request->has('current');
        if ($isCurrent) {
            ResultSession::query()->update(['current' => false]);
        }
        $session->update([
            'name' => $validated['name'],
            'period_id' => $currentPeriod->id,
            'current' => $isCurrent,
        ]);
        // Sécurité : s'assurer qu'une seule session est à true
        if ($isCurrent) {
            ResultSession::where('id', '!=', $session->id)->update(['current' => false]);
        }
        return redirect()->back()->with('success', 'Session modifiée avec succès.');
    }

    public function destroy(ResultSession $session)
    {
        $session->delete();
        return redirect()->back()->with('success', 'Session supprimée.');
    }
}
