<?php

namespace App\Http\Controllers;

use App\Enums\StudentPromotionStatus;
use App\Models\Period;
use App\Models\Student;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::paginate(15);
        return view('students.index', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matricule' => 'required|string|max:255|unique:students,matricule',
            'promotion_id' => 'required|exists:promotions,id',
        ]);
        $currentPeriod = Period::where('current', true)->first()->name ?? null;
        $student = Student::create([
            'name' => $validated['name'],
            'matricule' => $validated['matricule'],
        ]);

        $student->promotions()->attach($validated['promotion_id'], [
            'period' => $currentPeriod,
            'status' => StudentPromotionStatus::EN_COURS->value,
        ]);

        return redirect()->route('students.index')->with('success', 'Étudiant créé avec succès.');
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matricule' => ['required','string','max:255',Rule::unique('students')->ignore($student->id)],
            'promotion_id' => 'required|exists:promotions,id',
        ]);
        $student->update([
            'name' => $validated['name'],
            'matricule' => $validated['matricule'],
        ]);

        // Récupérer la promotion courante (status en cours)
        $currentPromotion = $student->promotions()->wherePivot('status', 'en cours')->first();

        if (!$currentPromotion) {
            // Si aucune promotion, attacher comme dans store
            $currentPeriod = Period::where('current', true)->first()->name ?? null;
            $student->promotions()->attach($validated['promotion_id'], [
                'period' => $currentPeriod,
                'status' => StudentPromotionStatus::EN_COURS->value,
            ]);
        } else {
            // Sinon, mettre à jour l'id de promotion dans la table pivot
            $student->promotions()->updateExistingPivot($currentPromotion->id, [
                'promotion_id' => $validated['promotion_id'],
            ]);
        }

        return redirect()->route('students.index')->with('success', 'Étudiant modifié avec succès.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Étudiant supprimé.');
    }
}
