<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    //project page, list all project
    public function index()
    {
        $groups = Groups::orderBy('created_at', 'desc')->get();
        return Inertia('Group/GroupPage', [
            'groups' => $groups
        ]);
    }
    public function store(Request $request)
    {
        // Validation rules
        $validator = $request->validate([
            'name' => 'required|string',
            'created_by' => 'required|exists:users,id',
        ]);
        //create group
        Groups::create([
            'name'=>$validator['name'],
            'created_by'=> $validator['created_by'],
        ]);
    }
}
