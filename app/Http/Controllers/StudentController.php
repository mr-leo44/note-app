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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'matricule' => 'required|string|max:255|unique:students,matricule',
            'gender' => 'required|string|max:1',
            'promotion_id' => 'required|exists:promotions,id',
        ]);
        $currentPeriod = Period::where('current', true)->first()->name ?? null;
        $student = Student::create([
            'name' => $validated['name'],
            'gender' => $validated['gender'],
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
            'gender' => 'string|max:1',
            'promotion_id' => 'required|exists:promotions,id',
        ]);
        $student->update([
            'name' => $validated['name'],
            'gender' => $validated['gender'],
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
        $currentPromotion = $student->promotions()->wherePivot('status', 'en cours')->first();
        $promotionCourses = DB::table('course_promotion')
            ->join('courses', 'course_promotion.course_id', '=', 'courses.id')
            ->where('promotion_id', $currentPromotion->id)
            ->select('course_promotion.*', 'courses.*')
            ->get();
        $notes = [];
        // dd($request->input('notes'), $promotionCourses, $promotionCourses->count());
        foreach ($request->input('notes') as $note) {
            foreach ($note as $courseKey => $value) {
                if (!is_null($value)) {
                    $course = $promotionCourses->where('name', $courseKey)->first();
                    // mettre note et credit en fonction du cours
                    $notes[$courseKey]['note'] = $value;
                    $notes[$courseKey]['credit'] = (int)$course->maxima;
                }
            }
        }
        if (empty($notes)) {
            return back()->with('warning', 'Vous ne pouvez pas envoyer un formulaire vide');
        } else {
            $totalCredits = 0;
            $noteByCourse = 0;
            $noteToStore = [];
            $validatedCourses = 0;
            foreach ($notes as $key => $value) {
                $totalCredits += $value['credit'];
                $noteByCourse += $value['note'] * $value['credit'];
                $noteToStore[$key] = $value['note'];
                if ($value['note'] >= 10 && $value['note'] <= 20) {
                    $validatedCourses++;
                }
            }
            $decision = $validatedCourses === count($notes) ? 'V' : 'NV';
            $semesterAverage = $noteByCourse / $totalCredits;
            $percentage = round(($semesterAverage / 20) * 100, 2);
            $status = $promotionCourses->count() === count($notes) ? StudentPromotionStatus::COMPLETE->value : StudentPromotionStatus::DRAFT->value;


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
            // dd($totalCredits, $noteByCourse, $semesterAverage, $noteToStore, $percentage, $mention, $decision, count($notes),$status);
            $currentResult = Result::where('student_id', $student->id)
                ->where('result_session_id', $currentSession)->first();
            if ($currentResult && $currentResult->count() > 0) {
                $result = $currentResult;
                $result->notes = $noteToStore;
                $result->mention = $mention;
                $result->percentage = $percentage;
                $result->status = $status;
                $result->average = $semesterAverage;
                $result->decision = $decision;
                $result->published_by = Auth::user()->id;
                $result->save();
            } else {
                $result = new Result();
                $result->student_id = $student->id;
                $result->result_session_id = $currentSession;
                $result->notes = $noteToStore;
                $result->mention = $mention;
                $result->percentage = $percentage;
                $result->status = $status;
                $result->average = $semesterAverage;
                $result->decision = $decision;
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

        if ($currentResult) {
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
