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
        return Inertia('Page/ProjectPage',[
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
}
