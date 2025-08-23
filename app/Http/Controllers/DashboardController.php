<?php

namespace App\Http\Controllers;

use App\Enums\ResultSemester;
use App\Enums\ResultSession as EnumsResultSession;
use Carbon\Carbon;
use App\Models\Jury;
use App\Models\Course;
use App\Models\Period;
use App\Models\Result;
use App\Models\Promotion;
use App\Models\ResultStatus;
use App\Models\ResultSession;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class DashboardController extends Controller
{
    public function dashboard()
    {
        Carbon::setLocale('fr');
        $currentPeriod = Period::where('current', true)->first();
        if(is_null($currentPeriod)) {
            $yearNow = now()->year;
            $currentPeriod = Period::create([
                "name" => $yearNow. "-" . (now()->year + 1),
                "current" => true
            ]);
        } else {
            $currentSemester = Semester::where('current', true)->first();
            if (is_null($currentSemester)) {
                $currentSemester = Semester::where('current', true)->first() ?? Semester::create([
                    "name" => ResultSemester::SEM1->label(),
                    "short_name" => ResultSemester::SEM1->name,
                    "current" => true,
                    "period_id" => $currentPeriod->id
                ]);
            } else {
                $currentSession = ResultSession::where('current', true)->first();
                if (is_null($currentSession)) {
                    $currentSession = ResultSession::create([
                        "name" => EnumsResultSession::S1->label(),
                        "short_name" => EnumsResultSession::S1->name,
                        "current" => true,
                        "semester_id" => $currentSemester->id
                    ]);
                }
            }
        }
        $courses = Course::all();
        $juries = Jury::all();
        $students = $currentPeriod ? DB::table('promotion_student')->where('period', $currentPeriod->name)->where('status', 'en cours')->get() : null;
        
        $promotions = Promotion::paginate();
        if($promotions->items() !== []) {
            foreach ($promotions as $promotion) {
                $publishedResultByPromotion = 0;
                $studentsByPromotion = $promotion
                    ->students()
                    ->wherePivot('promotion_id', $promotion->id)
                    ->wherePivot('period', $currentPeriod->name)
                    ->wherePivot('status', 'en cours')
                    ->get();
                foreach ($studentsByPromotion as $student) {
                    $studentResult = \App\Models\Result::where('student_id', $student->id)
                        ->where('result_session_id', $currentSession->id)
                        ->where('status', 'publié')
                        ->first();
                    if ($studentResult) {
                        $publishedResultByPromotion++;
                    }
                }
                $promotion['publishedResultByPromotion'] = $publishedResultByPromotion;
                
                $resultStatus =
                ResultStatus::where('promotion_id', $promotion->id)
                ->where('session', $currentSession->id)
                ->first();
                $promotion['statusOfResult'] = $resultStatus;
            }
        }

        return view('dashboard', compact('currentPeriod', 'currentSemester', 'currentSession', 'promotions', 'courses', 'juries', 'students'));
    }
}
