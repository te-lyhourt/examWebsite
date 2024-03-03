<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    //project page, list all project
    public function index(){
        $projects = Projects::all();
        return Inertia('ProjectPage',[
            'projects'=>$projects
        ]);
    }
}
