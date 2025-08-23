<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use App\Models\Semester;
use App\Models\Promotion;
use App\Models\ResultStatus;
use Illuminate\Http\Request;
use App\Models\ResultSession;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\ResultByPromotionStatus;

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
        $request->validate([
            'matricule' => 'required|string',
            'semester' => 'required|integer|exists:semesters,id',
        ]);

        $student = Student::where('matricule', $request->matricule)->first();
        if (is_null($student)) {
            $warning = nl2br("Le matricule que vous avez saisi est incorrect ou n'existe pas dans notre base de données.");
            return back()->with('warning', $warning);
        } else {
            $currentPromotion = $student->promotions()
                ->where('status', 'en cours')
                ->first();
        }
        $semester = Semester::findOrFail($request->semester);
        return view('results', compact(['student', 'currentPromotion', 'semester']));
    }

    public function pdf($studentId, $sessionId, Request $request)
    {
        // charge les données essentielles (respecter les relations et validations en prod)
        $student = Student::findOrFail($studentId);
        $session = ResultSession::findOrFail($sessionId);

        // promotion (optionnel) — récupération best-effort
        $promotionId = $request->query('promotion') ?? $student->promotion_id ?? null;
        $promotion = $promotionId ? \App\Models\Promotion::find($promotionId) : null;

        // courses for promotion
        $coursesByPromotion = collect();
        if ($promotion) {
            $coursesByPromotion = DB::table('course_promotion')
                ->join('courses', 'course_promotion.course_id', '=', 'courses.id')
                ->where('course_promotion.promotion_id', $promotion->id)
                ->select('courses.*', 'course_promotion.*')
                ->get();
        }


        // try get the result for the semester (first session / aggregate view)
        $result = \App\Models\Result::where('student_id', $student->id)
            ->whereHas('resultSession', function ($q) use ($session) {
                $q->where('result_session_id', $session->id);
            })
            ->first();

        $data = [
            'student' => $student,
            'session' => $session,
            'promotion' => $promotion,
            'coursesByPromotion' => $coursesByPromotion,
            'result' => $result,
        ];

        $pdf = Pdf::loadView('pdf', $data);

        // Stream in browser (download => ->download('resultat.pdf'))
        return $pdf->stream("resultats-{$student->name}-{$session->short_name}-{$session->semester->short_name}-{$session->semester->period->name}.pdf");
    }
}
