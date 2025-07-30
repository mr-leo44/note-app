<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Jury;
use App\Models\Course;
use App\Models\Period;
use App\Models\Result;
use App\Models\Promotion;
use App\Models\ResultStatus;
use App\Models\ResultSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class DashboardController extends Model
{
    public function dashboard()
    {
        Carbon::setLocale('fr');
        $currentPeriod = Period::where('current', true)->first();
        $currentSession = $currentPeriod ? ResultSession::where('current', true)->where('period_id', $currentPeriod->id)->first() : null;
        $promotions = Promotion::paginate();
        $courses = Course::all();
        $juries = Jury::all();
        $students = $currentPeriod ? DB::table('promotion_student')->where('period', $currentPeriod->name)->where('status', 'en cours')->get() : null;
        $publishedResultByPromotion = 0;
        foreach ($promotions as $promotion) {
            $studentsByPromotion = $promotion
                ->students()
                ->wherePivot('promotion_id', $promotion->id)
                ->wherePivot('period', $currentPeriod->name)
                ->wherePivot('status', 'en cours')
                ->get();
            foreach ($studentsByPromotion as $student) {
                $studentResult = \App\Models\Result::where('student_id', $student->id)
                    ->where('result_session_id', $currentSession->id)
                    ->first();
                if ($studentResult) {
                    $publishedResultByPromotion++;
                }
            }

            $resultStatus =
                \App\Models\ResultStatus::where('promotion_id', $promotion->id)
                ->where('session', $currentSession->id)
                ->first();
            $promotion['resultStatus'] = $resultStatus;
            dd($currentSession, $juries, $promotions, $currentPeriod);
        }

        return view('dashboard', compact('currentPeriod', 'currentSession', 'promotions', 'courses', 'juries', 'students', 'publishedResultByPromotion'));
    }
}
