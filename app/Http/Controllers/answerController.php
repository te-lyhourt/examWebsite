<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\Answers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use ZipArchive;

class answerController extends Controller
{
    //create new answer
    public function store(Request $request)
    {
        // Validation rules
        $validator = $request->validate([
            'questions_id' => 'required|exists:questions,id',
            'user_id' => 'required|exists:users,id',
        ]);
        //check if file
        if ($request->file()) {
            //deal with file
            // $username = strstr($request->user_email, '@', true);
            $fileName =  "questions_".$request->questions_id . "_" . $request->file('file')->getClientOriginalName();
            $path = $request->project_id . "/" . strstr($request->user_email, '@', true) . "/" .$fileName;

            Gdrive::put($path, $request->file('file'));

            $url = Storage::disk('google')->url($path);

            Answers::create([
                'questions_id' => $validator['questions_id'],
                'user_id' => $validator['user_id'],
                'answer' => $request->answer,
                'fileName' => $fileName,
                'filePath'=>$path,
                'repeatIndex'=>$request->repeatIndex,
                'url'=>$url
            ]);
        } else {
            // create group
            Answers::create([
                'questions_id' => $validator['questions_id'],
                'user_id' => $validator['user_id'],
                'answer' => $request->answer,
                'repeatIndex'=>$request->repeatIndex,
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $answers = Answers::find($id);
        if ($request->updateFile) {
            $username = strstr($request->user_email, '@', true);
            $fileName =  "questions_".$request->questions_id . "_" . $request->file('file')->getClientOriginalName();
            $path = $request->project_id . "/" . strstr($request->user_email, '@', true) . "/" .$fileName;

            Gdrive::delete($request->oldPath);
            Gdrive::put($path, $request->file('file'));
            $url = Storage::disk('google')->url($path);
            $answers->url = $url;

            $answers->fileName = $fileName;
            $answers->filePath = $path;
        }

        $answers->answer = $request->answer;

        $answers->save();
    }
    public function export(Request $request, $id)
    {
        $url = Storage::disk('google')->url($id);
        return Inertia::location($url);
    }
}
