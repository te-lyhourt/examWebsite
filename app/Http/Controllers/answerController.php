<?php

namespace App\Http\Controllers;

use App\Models\Answers;
use Illuminate\Http\Request;

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
            if($request->file()){
                //deal with file
                dd($request->file('file')->store("","google"));
            }
            else{
                // create group
                Answers::create([
                    'questions_id' => $validator['questions_id'],
                    'user_id' => $validator['user_id'],
                    'answer'=>$request->answer,
                ]);
            }
        }
        public function update(Request $request,$id)
        {
            $answers = Answers::find($id);
            $answers->answer = $request->answer;
            $answers->save();
        }
}
