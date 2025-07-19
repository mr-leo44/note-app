<?php

namespace App\Http\Controllers;

use App\Enums\ResultByPromotionStatus;
use App\Enums\ResultMention;
use App\Models\Jury;
use App\Models\Period;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Enums\StudentPromotionStatus;
use App\Models\ResultStatus;

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
            'matricule' => ['required', 'string', 'max:255', Rule::unique('students')->ignore($student->id)],
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

    public function assignResults(Request $request, Student $student, $currentPeriodId)
    {
        $request->validate([
            'notes' => 'required|array|min:1',
        ]);

        $currentPromotion = $student->promotions()->first();
        $session = Period::where('id', $currentPeriodId)->first()->name ?? null;
        $promotionCoursesMaximas = DB::table('course_promotion')
            ->where('promotion_id', $currentPromotion->id)
            ->get()->pluck('maxima')->toArray();
        $totalNotes = array_sum($promotionCoursesMaximas);
        $studentNotes = array_sum($request->input('notes'));
        $percentage = ($studentNotes / $totalNotes);

        switch ($percentage) {
            case $percentage >= 50 || $percentage < 70:
                $mention = ResultMention::S->value;
                break;
            case $percentage < 0.8:
                $mention = ResultMention::D->value;
                break;
            case $percentage < 0.9:
                $mention = ResultMention::GD->value;
                break;
            case $percentage >= 90:
                $mention = ResultMention::TGD->value;
                break;
            default:
                $mention = ResultMention::A->value;
        }

        $result = Result::create([
            'student_id' => $student->id,
            'period_id' => $currentPeriodId,
            'session' => $request->input('session'),
            'notes' => $request->input('notes'),
            'mention' => $mention,
            'percentage' => round(number_format($percentage * 100, 2)),
            'published_by' => Auth::user()->id,
        ]);

        if ($result) {
            if (!(DB::table('result_status')->where('promotion_id', $currentPromotion->id)
                ->where('session', $session)
                ->where('period', $currentPeriodId)
                ->exists())) {
                ResultStatus::create([
                    'period' => $currentPeriodId,
                    'session' => $session,
                    'status' => ResultByPromotionStatus::DRAFT->value,
                    'promotion_id' => $currentPromotion->id,
                ]);
            }
        }


        return redirect()->route('students.index')->with('success', "Résultats assignés à l'étudiant {$student->name} pour la session {$session}.");
    }
}
