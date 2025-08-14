<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\ResultSession;
use App\Enums\ResultSession as EnumResultSession;

class SessionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'current' => 'nullable|boolean',
        ]);

        $currentSemester = Semester::where('current', true)->first();
        $sessionName = $request['name'] === EnumResultSession::S1->value ? EnumResultSession::S1->label() : EnumResultSession::S2->label();
        $sessionShortName = $request['name'] === EnumResultSession::S1->value ? EnumResultSession::S1->name : EnumResultSession::S2->name;
        if (ResultSession::where('name', $request['name'])->where('semester_id', $currentSemester->id)->exists()) {
            return redirect()->back()->with('warning', 'Cette session existe déjà.');
        } else {
            $isCurrent = $request->has('current');
            if ($isCurrent) {
                // Mettre toutes les périodes à false
                ResultSession::query()->update(['current' => false]);
            }
            $session = ResultSession::create([
                'name' => $sessionName,
                'short_name' => $sessionShortName,
                'semester_id' => $currentSemester->id,
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
            'current' => 'nullable|boolean',
        ]);

        $currentSemester = Semester::where('current', true)->first();
        $isCurrent = $request->has('current');
        if ($isCurrent) {
            ResultSession::query()->update(['current' => false]);
        }
        $session->update([
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
