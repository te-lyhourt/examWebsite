<?php

namespace App\Http\Controllers;

use App\Models\Questions;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function delete(Request $request)
    {
        $validator = $request->validate([
            //validataion rule
            'questions.*' => 'required|integer|exists:questions,id'
        ]);
        Questions::whereIn('id', $validator['questions'])->delete();
    }

    //create new question
    public function store(Request $request)
    {
        // Validation rules
        $validator = $request->validate([
            'type' => "required|string",
            'projects_id' => "exists:projects,id",
            'fileUpload' => "string",
        ]);
        if(gettype($request->description)=='string'){
            
            // for ($x = 0; $x < $request->repeatNum; $x++) {
                //create group
                Questions::create([
                    'description' => $request->description,
                    'type' => $validator['type'],
                    'projects_id' => $validator['projects_id'],
                    'fileUpload' => $validator['fileUpload'],
                    'options' => json_encode($request->options)
                ]);
            // }
        }
        
        if(gettype($request->description)=='array'){
            foreach ($request->description as $question) {
                // for ($x = 0; $x < $request->repeatNum; $x++) {
                    //create group
                    Questions::create([
                        'description' => $question,
                        'type' => $validator['type'],
                        'projects_id' => $validator['projects_id'],
                        'fileUpload' => $validator['fileUpload'],
                        'options' => json_encode($request->options)
                    ]);
                // }

            }

        }
        
    }
}
