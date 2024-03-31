<?php

namespace App\Http\Controllers;

use App\Models\Questions;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function delete(Request $request){
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
                'description'=> "required|string",
                'type' => "required|string",
                'projects_id'=> "exists:projects,id",
                'fileUpload'=>"string",
            ]);
            //create group
            Questions::create([
                'description' => $validator['description'],
                'type' => $validator['type'],
                'projects_id'=> $validator['projects_id'],
                'fileUpload'=>$validator['fileUpload'],
                'options'=>json_encode($request->options)
            ]);
        }
}
