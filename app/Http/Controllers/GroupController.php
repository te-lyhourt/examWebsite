<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    //project page, list all project
    public function index(){
        $groups = Groups::all();
        return Inertia('GroupPage',[
            'groups'=>$groups
        ]);
    }
}
