<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use App\Models\SurveyQuestion;
use Illuminate\Validation\Rule;
use App\Models\SurveyQuestionAnswer;
use Illuminate\Support\Facades\File;
use App\Http\Resources\SurveyResource;
use App\Http\Requests\StoreSurveyRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateSurveyRequest;
use App\Http\Requests\StoreSurveyAnswerRequest;


class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $user = $req->user();
        return SurveyResource::collection(Survey::where('user_id', $user->id)->paginate(5));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSurveyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSurveyRequest $request)
    {
        //
        $data = $request->validated();

        if(isset($data['image'])){
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;
        }

        $survey = Survey::create($data);

        //New questions

        foreach($data['questions'] as $question){
            $question['survey_id'] = $survey->id;
            $this->createQuestion($question);
        }
        return new SurveyResource($survey);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey, Request $request)
    {
        //
        $user = $request->user();
        if($user->id !== $survey->user_id){
            return abort(403, 'Unauthorized action');
        }
        return new SurveyResource($survey);

    }

    public function showForGuest(Survey $survey)
    {
        //
        return new SurveyResource($survey);

    }

    public function storeAnswer(Survey $survey, StoreSurveyAnswerRequest $request){
        $validated = $request->validated();
        $surveyAnswer = SurveyAnswer::create([
            'survey_id' => $survey->id,
            'start_date' => date('Y-m-d H:i:s'),
            'end_date' => date('Y-m-d H:i:s'),
        ]);

        foreach($validated['answers'] as $questionId => $answer){
            $question = SurveyQuestion::where(['id' => $questionId, 'survey_id' => $survey->id])->get();
            if(!$question){
                return response("Invalid question ID:\"$questionId\"", 400);

            }
            $data = [
                'survey_question_id' => $questionId,
                'survey_answer_id' => $surveyAnswer->id,
                'answer' => is_array($answer) ? json_encode($answer) : $answer,
            ];

            SurveyQuestionAnswer::create($data);
        }

        return response("", 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSurveyRequest  $request
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        //
        $data = $request->validated();

        if(isset($data['image'])){
            $relativePath = $this->saveImage($data['image']);
            $data['image'] = $relativePath;

            if($survey->image){
                $absolutePath = public_path($survey->image);
                File::delete($absolutePath);
            }
        }

        $survey->update($data);

        //Get ids as plan array of existing questions
        $existingIds = $survey->questions()->pluck('id')->toArray();

        //Get ids as plan array for new questions
        $newIds = Arr::pluck($data['questions'], 'id');

        //Find questions to delete
        $toDelete = array_diff($existingIds, $newIds);

        //Find questions to add
        $toAdd = array_diff($newIds, $existingIds);

        //Delete questions by $toDelete array
        SurveyQuestion::destroy($toDelete);

        //Create new questions
        foreach($data['questions'] as $question){
            if(in_array($question['id'], $toAdd)){
                $question['survey_id'] = $survey->id;
                $this->createQuestion($question);
            }
        }

        //Update existing questions
        $questionMap = collect($data['questions'])->keyBy('id');
        foreach($survey->questions as $question){
            if(isset($questionMap[$question->id])){
                $this->updateQuestion($question, $questionMap[$question->id]);
            }
        }

        return new SurveyResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey, Request $request)
    {
        //
        $user = $request->user();
        if($user->id !== $survey->user_id){
            return abort(403, 'Unauthorized action');
        }
        $survey->delete();

        if($survey->image){
            $absolutePath = public_path($survey->image);
            File::delete($absolutePath);
        }

        return response()->json('Survey deleted', 201);
    }

    private function saveImage($image)
    {
        if(preg_match('/^data:image\/(\w+);base64,/', $image, $type)){

            //take base64 encode text
            $image = substr($image, strpos($image, ',') + 1);

            //Get file extension
            $type = strtolower($type[1]);

            //Check if file is an image
            if(!in_array($type,['jpg','jpeg','gif','png'])){
                throw new \Exception('Invalid image');
            }
            $image = str_replace('','+',$image);
            $image = base64_decode($image);

            if($image === false){
                throw new \Exception ('base64_decode failed');
            }

        }else{
            throw new \Exception('did not match data URI with image data');
        }

        $dir = 'images/';
        $file = Str::random().'.'.$type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;

        if(!File::exists($absolutePath)){
            File::makeDirectory($absolutePath, 0755, true);
        }

        file_put_contents($relativePath, $image);

        return $relativePath;
    }

    private function createQuestion($data){
        if(is_array($data['data'])){
            $data['data'] = json_encode($data['data']);
        }

        $validator = Validator::make($data, [
            'question' => 'required|string',
            'type' => ['required', Rule::in([
                Survey::TYPE_TEXT,
                Survey::TYPE_TEXTAREA,
                Survey::TYPE_SELECT,
                Survey::TYPE_RADIO,
                Survey::TYPE_CHECKBOX,
            ])],
            'description' => 'nullable|string',
            'data' => 'present',
            'survey_id' => 'exists:App\Models\Survey,id'
        ]);
        // dd($validator);
        return SurveyQuestion::create($validator->validate());
    }

    private function updateQuestion(SurveyQuestion $question, $data){
        if(is_array($data['data'])){
            $data['data'] = json_encode($data['data']);
        }

        $validator = Validator::make($data, [
            'question' => 'required|string',
            'type' => ['required', Rule::in([
                Survey::TYPE_TEXT,
                Survey::TYPE_TEXTAREA,
                Survey::TYPE_SELECT,
                Survey::TYPE_RADIO,
                Survey::TYPE_CHECKBOX,
            ])],
            'description' => 'nullable|string',
            'data' => 'present',
            'id' => 'exists:App\Models\SurveyQuestion,id'
        ]);

        return $question->update($validator->validated());
    }
}
