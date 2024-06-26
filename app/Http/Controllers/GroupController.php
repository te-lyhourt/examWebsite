<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\Projects;
use App\Models\User;
use Google\Service\CloudResourceManager\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userRole = json_decode($user->role)[0];

        if ($userRole == 'system admin') {
            $groups = Groups::orderBy('created_at', 'desc')->get();
            return Inertia('Group/GroupPage', [
                'groups' => $groups
            ]);
        }
        if ($userRole == 'project admin') {
            $groups = Groups::orWhere('created_by', $user->id)->orderBy('created_at', 'desc')->get();
            return Inertia('Group/GroupPage', [
                'groups' => $groups
            ]);
        }
        if ($userRole == 'user') {
            return redirect()->route('page.project');
        }
    }
    public function store(Request $request)
    {
        // Validation rules
        $validator = $request->validate([
            'name' => 'required|string|unique:groups,name',
            'created_by' => 'required|exists:users,id',
        ]);
        //create group
        Groups::create([
            'name' => $validator['name'],
            'created_by' => $validator['created_by'],
        ]);
    }
    public function groupDetail(Request $request)
    {
        $user = Auth::user();
        $userRole = json_decode($user->role)[0];
        if ($userRole == 'user') {
            return redirect()->route('page.project');
        }

        $request->merge(['id' => $request->route('id')]);

        $validator = $request->validate([
            'id' => 'required|integer|exists:groups,id'
        ]);
        $group = Groups::findOrFail($validator['id']);
        return Inertia('Group/GroupDetail', [
            'group' => $group,
            'users' => $group->load('users')
        ]);
    }

    public function addUser(Request $request, $id)
    {

        $validatUser = $request->validate(
            //validataion rule
            [
                'users.*' => 'required|email|max:100|exists:users,email',
            ],
            //custome error messsage
            [
                'users.*.required' => 'Error at :position: email is required.',
                'users.*.email' => 'Error at :position: The :input must be a valid email address.',
                'users.*.exists' => 'Error at :position: The :input does not exist.',

            ]
        );
        $userIDs = User::whereIn('email', $validatUser['users'])->pluck('id');
        $users = [];
        foreach ($userIDs as $userID) {
            $users[$userID] = [
                'added_by' => auth()->id()
            ];
        }
        $group = Groups::find($id);
        $group->users()->syncWithoutDetaching($users);
    }

    public function removeUser(Request $request, $id)
    {
        // Validate the request data if needed
        $validat = $request->validate([
            'users.*' => 'required|integer|exists:users,id'
        ]);

        // Find the group
        $group = Groups::find($id);

        // Detach users from the group
        $group->users()->detach($validat['users']);
    }

    public function createUser(Request $request, $id)
    {
        // Validation rules
        $validator = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'created_by' => 'required|integer|exists:users,id',
        ]);

        // Create user
        $user = User::create([
            'email' => $validator['email'],
            'password' => bcrypt($validator['password']),
            'role' => json_encode([$validator['role']]),
            'created_by' => $validator['created_by'],
        ]);
        $group = Groups::find($id);

        $users = [];
        $users[$user->id] = [
            'added_by' => auth()->id()
        ];
        $group->users()->syncWithoutDetaching($users);
    }
    //delete group
    public function delete(Request $request)
    {
        $validator = $request->validate([
            //validataion rule
            'groups.*' => 'required|integer|exists:groups,id'
        ]);
        Groups::whereIn('id', $validator['groups'])->delete();
    }
    //progress
    public function progress(Request $request, $group_id, $project_id)
    {
        $project = Projects::findOrFail($project_id);
        $totalQuestions = $project->questions()->count() * $project->repeatNum;
        $group = Groups::findOrFail($group_id);
        $group->totalQuestions = $totalQuestions;
        $group->projectName = $project->name;
        $group->project_id = $project_id;
        // Get all question IDs associated with the project
        $questionIds = $project->questions()->pluck('id');

        // Load users with their answers for the specified questions
        $users = $group->users->map(function ($user) use ($questionIds) {
            // Count the number of answers for the specified questions for each user
            $user->answersCount = $user->answers()->whereIn('questions_id', $questionIds)->count();
            return $user;
        });

        return Inertia('Project/userProgress', [
            'group' => $group,
            'users' => $users
        ]);
    }
    public function progressDetail(Request $request, $user_id, $project_id){
        $project = Projects::findOrFail($project_id);
        $project = $project->load(['questions.answers' => function ($query) use ($user_id){
            $query->where('user_id', $user_id);
        }]);
        $user = User::findOrFail($user_id);
        $project->user = $user;
        return Inertia('Project/userProgressDetail', [
            'project' => $project,
        ]);
    }
}
