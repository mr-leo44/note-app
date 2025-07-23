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
use App\Models\ResultSession;
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

        return redirect()->back()->with('success', 'Étudiant créé avec succès.');
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

        return redirect()->back()->with('success', 'Étudiant modifié avec succès.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->back()->with('success', 'Étudiant supprimé.');
    }

    public function assignResults(Request $request, Student $student, $currentSession)
    {
        $request->validate([
            'notes' => 'required|array|min:1',
        ]);
        $currentPromotion = $student->promotions()->first();
        $coursePromotionCount = DB::table('course_promotion')
            ->where('promotion_id', $currentPromotion->id)
            ->count();
        $notes = [];
        foreach ($request->input('notes') as $note) {
            foreach ($note as $course => $value) {
                if (!is_null($value)) {
                    $notes[$course] = $value; // Store the note with course_id as key
                }
            }
        }
        if(empty($notes)) {
            return back()->with('warning', 'Vous ne pouvez pas envoyer un formulaire vide');
        } else {
            $studentResultCount = count($notes);
            $promotionCoursesMaximas = DB::table('course_promotion')
                ->where('promotion_id', $currentPromotion->id)
                ->get()->pluck('maxima')->toArray();
            $totalNotes = array_sum($promotionCoursesMaximas);
            $studentNotes = collect($request['notes'])->sum(function ($item) {
                return collect($item)->first(); // ou array_values($item)[0]
            });
            $percentage = round(number_format(($studentNotes / $totalNotes) * 100, 2), 2);
            switch ($percentage) {
                case $percentage >= 50 && $percentage < 70:
                    $mention = ResultMention::S->value;
                    break;
                case $percentage < 80 && $percentage >= 70:
                    $mention = ResultMention::D->value;
                    break;
                case $percentage < 90 && $percentage >= 80:
                    $mention = ResultMention::GD->value;
                    break;
                case $percentage >= 90:
                    $mention = ResultMention::TGD->value;
                    break;
                default:
                    $mention = ResultMention::A->value;
            }
            $currentResult = Result::where('student_id', $student->id)
                ->where('result_session_id', $currentSession)->first();
                $status = $studentResultCount === $coursePromotionCount ? StudentPromotionStatus::COMPLETE->value : StudentPromotionStatus::DRAFT->value;
            if ($currentResult && $currentResult->count() > 0) {
                $result = $currentResult;
                $result->notes = $notes;
                $result->mention = $mention;
                $result->percentage = $percentage;
                $result->status = $status;
                $result->published_by = Auth::user()->id;
                $result->save();
            } else {
                $result = new Result();
                $result->student_id = $student->id;
                $result->result_session_id = $currentSession;
                $result->notes = $notes;
                $result->mention = $mention;
                $result->percentage = $percentage;
                $result->status = $status;
                $result->published_by = Auth::user()->id;
                $result->save();
            }
    
            if ($result) {
                if (!(DB::table('result_status')->where('promotion_id', $currentPromotion->id)
                    ->where('session', $currentSession)
                    ->exists())) {
                    ResultStatus::create([
                        'session' => $currentSession,
                        'status' => ResultByPromotionStatus::DRAFT->value,
                        'promotion_id' => $currentPromotion->id,
                    ]);
                }
            }
    
            return back()->with('success', "Résultats assignés à l'étudiant {$student->name} pour la session en cours.");
        }
    }

    public function publishResult(Request $request, Student $student, $result)
    {
        $currentSession = ResultSession::where('current', true)->first();
        $currentPromotion = $student->promotions()->first();
        $currentResult = Result::find($result);
        $currentResult->status = StudentPromotionStatus::PUBLISHED->value;
        $currentResult->save();

        if($currentResult) {
            $promotionResultStatus = ResultStatus::where('promotion_id', $currentPromotion->id)
                ->where('session', $currentSession->id)
                ->first();
            $promotionStudents = $currentPromotion->students()->wherePivot('status', 'en cours')->get();
            $resultsCount = 0;
            foreach ($promotionStudents as $promotionStudent) {
                $studentResult = Result::where('student_id', $promotionStudent->id)->where('result_session_id', $currentSession->id)->first();
                if ($studentResult->status === StudentPromotionStatus::PUBLISHED->value) $resultsCount += 1;
            }
    
            if ($promotionStudents->count() === $resultsCount) {
                $promotionResultStatus->status = ResultByPromotionStatus::COMPLETE->value;
                $promotionResultStatus->save(); 
            }
        }
        return response()->json();
    }
}
