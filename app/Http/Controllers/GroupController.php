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
    public function groupDetail(Request $request){
        $request->merge(['id' => $request->route('id')]);

        $validator = $request->validate([
            'id' => 'required|integer|exists:groups,id'
        ]);
        $group = Groups::findOrFail($validator['id']);
        return Inertia('Group/GroupDetail', [
            'group' => $group,
            'users'=>$group->load('users')
        ]);
    }

    public function addUser(Request $request,$id){

        $users = [];
        foreach($request->users as $userID){
            $users[$userID] = [
                'added_by'=>auth()->id()
            ];
        }
        $group = Groups::find($id);
        $group->users()->syncWithoutDetaching($users);
    }

    // public function showUser(Groups $group){
    //     return $group->load('users');
    // }
}
