<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use App\Models\ResultStatus;
use Illuminate\Http\Request;
use App\Models\ResultSession;
use App\Enums\ResultByPromotionStatus;
use App\Models\Semester;

class ResultController extends Controller
{
    public function index()
    {
        $currentSession = ResultSession::where('current', true)->first();
        $publications = ResultStatus::where('session', $currentSession->id)->where('status', '!=', ResultByPromotionStatus::DRAFT->value)->paginate(10);
        return view('publications.index', compact('publications'));
    }

    public function onlinePublishResults(Request $request)
    {
        $currentSession = ResultSession::where('current', true)->first();
        $completedResults = ResultStatus::where('session', $currentSession->id)
            ->where('status', ResultByPromotionStatus::COMPLETE->value)
            ->get();
        foreach ($completedResults as $completedResult) {
            $completedResult->update([
                'status' => ResultByPromotionStatus::PUBLISHED->value,
            ]);
        }
        return response()->json();
    }

    public function search(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'matricule' => 'required|string',
            'semester' => 'required|integer|exists:semesters,id',
        ]);

        $student = Student::where('matricule', $request->matricule)->firstOrFail();
        if (!$student) {
            return back()->with('error', 'Étudiant non trouvé');
        } else {
            $currentPromotion = $student->promotions()
                ->where('status', 'en cours')
                ->first();
        }
        $semester = Semester::findOrFail($request->semester);
        // dd($semester, $currentPromotion, $student);
        return view('results', compact(['student', 'currentPromotion', 'semester']));
    }
}
