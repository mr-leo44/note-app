<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Enums\ResultSemester;

class SemesterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'current' => 'nullable|boolean',
        ]);

        $currentPeriod = Period::where('current', true)->first();
        $semesterName = $request['name'] === ResultSemester::SEM1->value ? ResultSemester::SEM1->label() : ResultSemester::SEM2->label();
        $semesterShortName = $request['name'] === ResultSemester::SEM1->value ? ResultSemester::SEM1->name : ResultSemester::SEM2->name;
        if (Semester::where('name', $request['name'])->where('period_id', $currentPeriod->id)->exists()) {
            return redirect()->back()->with('warning', 'Cette session existe déjà.');
        } else {
            $isCurrent = $request->has('current');
            if ($isCurrent) {
                // Mettre toutes les périodes à false
                Semester::query()->update(['current' => false]);
            }
            $semester = Semester::create([
                'name' => $semesterName,
                'short_name' => $semesterShortName,
                'period_id' => $currentPeriod->id,
                'current' => $isCurrent,
            ]);
            // Sécurité : s'assurer qu'une seule session est à true
            if ($isCurrent) {
                Semester::where('id', '!=', $semester->id)->update(['current' => false]);
            }
            return redirect()->back()->with('success', 'Semestre créé avec succès.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Semester $semester)
    {
        $sessions = $semester->result_sessions()->where('semester_id', $semester->id)->orderByDesc('name')->paginate(15);
        return view('semesters.show', compact('semester', 'sessions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Semester $semester)
    {
        $validated = $request->validate([
            'current' => 'nullable|boolean',
        ]);

        $currentPeriod = Period::where('current', true)->first();
        $isCurrent = $request->has('current');
        if ($isCurrent) {
            Semester::query()->update(['current' => false]);
        }
        $semester->update([
            'current' => $isCurrent,
        ]);
        // Sécurité : s'assurer qu'un seul semestre est à true
        if ($isCurrent) {
            Semester::where('id', '!=', $semester->id)->update(['current' => false]);
        }
        return redirect()->back()->with('success', 'Semestre modifié avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Semester $semester)
    {
        $semester->delete();
        return redirect()->back()->with('success', 'Semestre supprimé avec succès.');
    }
}
