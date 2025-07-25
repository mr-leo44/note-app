<?php

namespace App\Http\Controllers;

use App\Enums\ResultByPromotionStatus;
use Illuminate\Http\Request;
use App\Models\ResultSession;
use App\Models\ResultStatus;

class ResultController extends Controller
{
    public function index()
    {
        $currentSession = ResultSession::where('current', true)->first();
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
