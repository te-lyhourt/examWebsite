<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroupUserController extends Controller
{
    public function addUserToGroup(Request $request){
        $validator = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'user_id' => 'required|exists:users,id',
        ]);
    }
}
