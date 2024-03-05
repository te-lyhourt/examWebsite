<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    //project page, list all project
    public function index(){
        $projects = Projects::orderBy('created_at', 'desc')->get();
        return Inertia('Project/ProjectPage',[
            'projects'=>$projects
        ]);
    }
    //create new project
    public function store(Request $request)
    {
        // Validation rules
        $validator = $request->validate([
            'name' => 'required|string',
            'created_by' => 'required|exists:users,id',
        ]);
        //create group
        Projects::create([
            'name'=>$validator['name'],
            'created_by'=> $validator['created_by'],
        ]);
    }
    public function groupDetail(Request $request){
        $request->merge(['id' => $request->route('id')]);

        $validator = $request->validate([
            'id' => 'required|integer|exists:projects,id'
        ]);
        $project = Projects::findOrFail($validator['id']);
        return Inertia('Project/ProjectDetail', [
            'project' => $project,
            'groups'=>$project->load('groups')
        ]);
    }

    public function addGroup(Request $request,$id){

        $groups = [];
        foreach($request->groups as $groupID){
            $groups[$groupID] = [
                'added_by'=>auth()->id()
            ];
        }
        $project = Projects::find($id);
        $project->groups()->syncWithoutDetaching($groups);
    }

}
