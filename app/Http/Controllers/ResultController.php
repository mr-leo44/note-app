<?php

namespace App\Http\Controllers;

use App\Enums\ResultByPromotionStatus;
use App\Enums\StudentPromotionStatus;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\ResultSession;
use App\Models\ResultStatus;

class ResultController extends Controller
{
    public function index()
    {
        // $promotions = Promotion::all();
        $currentSession = ResultSession::where('current', true)->first();
        // foreach ($promotions as $promotion) {
        //     $resultByPromotion = ResultStatus::where('promotion_id', $promotion->id)->where('session', $currentSession->id)->first();
        //     if($resultByPromotion && $resultByPromotion->count() > 0) {
        //         $promotionStudents = $promotion->students()->wherePivot('status', 'en cours')->get();
        //         $resultsCount = 0;
        //         foreach ($promotionStudents as $promotionStudent) {
        //             $studentResult = Result::where('student_id', $promotionStudent->id)->where('result_session_id', $currentSession->id)->first();
        //             if ($studentResult->status === StudentPromotionStatus::PUBLISHED->value) {
        //                 $resultsCount += 1;
        //             }
        //         }
        //         if ($promotionStudents->count() === $resultsCount && $resultByPromotion->status !== ResultByPromotionStatus::PUBLISHED->value) {
        //            $resultByPromotion->status = ResultByPromotionStatus::COMPLETE->value;
        //            $resultByPromotion->save(); 
        //         }
        //     }
        // }
        $publications = ResultStatus::
        where('session', $currentSession->id)->where('status', '!=', ResultByPromotionStatus::DRAFT->value)->
        paginate(10);
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
}
