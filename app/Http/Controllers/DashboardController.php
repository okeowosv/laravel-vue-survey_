<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use App\Http\Resources\SurveyResource;
use App\Http\Resources\SurveyAnswerResource;

class DashboardController extends Controller
{
    //
    public function index(Request $request){
        $user = $request->user();

        //Total surveys
        $total = Survey::query()->where('user_id',$user->id)->count();
        //Latest surveys
        $latest = Survey::query()->where('user_id',$user->id)->latest('created_at')->first();
        //Total answers
        $totalAnswers = SurveyAnswer::query()->join('surveys','survey_answers.survey_id','=','surveys.id')
        ->where('surveys.user_id', $user->id)
        ->count();
        //Latest 5
        $latestAnswers = SurveyAnswer::query()->join('surveys','survey_answers.survey_id','=','surveys.id')
        ->where('surveys.user_id', $user->id)
        ->orderBy('end_date', 'DESC')
        ->limit(5)
        ->getModels('survey_answers.*');


        return [
            'totalSuverys' => $total,
            'latestSuvery' => $latest ? new SurveyResource($latest) : null,
            'totalAnswers' => $totalAnswers,
            'latestAnswers' => SurveyAnswerResource::collection($latestAnswers)
        ];



    }
}
