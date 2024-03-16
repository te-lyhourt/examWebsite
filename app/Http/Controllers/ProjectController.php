<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ProjectController extends Controller
{
    //project page, list all project
    public function index()
    {

        $user = Auth::user();
        $userRole = json_decode($user->role)[0];


        if ($userRole == 'system admin') {
            $projects = Projects::orderBy('created_at', 'desc')->get();
            return Inertia('Project/ProjectPage', [
                'projects' => $projects
            ]);
        }
        if ($userRole == 'project admin') {
            //only list project where user is the admin of that project or project created by user

            $projects = Projects::where('admin', $user->id)->orWhere('created_by', $user->id)->orderBy('created_at', 'desc')->get();
            return Inertia('Project/ProjectPage', [
                'projects' => $projects
            ]);
        }
        if ($userRole == 'user') {
            //only list project where user is belong to

            // Retrieve the groups associated with the user
            $groups = $user->groups;
            // Initialize an array to store project IDs
            $projectIds = [];
            // Iterate over the groups
            foreach ($groups as $group) {
                // Retrieve the projects associated with each group and extract their IDs
                $projectIds = array_merge($projectIds, $group->projects()->pluck('projects.id')->toArray());
            }
            // Ensure uniqueness of project IDs
            $projectIds = array_unique($projectIds);
            $projects = Projects::whereIn('id', $projectIds)->get();
            return Inertia('Project/ProjectPage', [
                'projects' => $projects
            ]);
        }
    }
    //create new project
    public function store(Request $request)
    {
        // Validation rules
        $validator = $request->validate([
            'name' => 'required|string|unique:projects,name',
            'created_by' => 'required|exists:users,id',
        ]);
        //create group
        Projects::create([
            'name' => $validator['name'],
            'created_by' => $validator['created_by'],
        ]);
    }
    public function projectDetail(Request $request)
    {
        $request->merge(['id' => $request->route('id')]);

        $validator = $request->validate([
            'id' => 'required|integer|exists:projects,id'
        ]);
        $project = Projects::findOrFail($validator['id']);
        return Inertia('Project/ProjectDetail', [
            'project' => $project,
            'groups' => $project->load('groups')
        ]);
    }

    public function addGroup(Request $request, $id)
    {

        $groups = [];
        foreach ($request->groups as $groupID) {
            $groups[$groupID] = [
                'added_by' => auth()->id()
            ];
        }
        $project = Projects::find($id);
        $project->groups()->syncWithoutDetaching($groups);
    }
    public function removeGroup(Request $request, $id)
    {
        // Validate the request data if needed
        $validat = $request->validate([
            'groups.*' => 'required|integer|exists:users,id'
        ]);

        // Find the group
        $project = Projects::find($id);

        // Detach users from the group
        $project->groups()->detach($validat['groups']);
    }
    public function delete(Request $request){
        $validator = $request->validate([
            //validataion rule
            'projects.*' => 'required|integer|exists:projects,id'
        ]);
        Projects::whereIn('id', $validator['projects'])->delete();
    }

    public function addAdmin(Request $request, $id)
    {
        // Find the project by ID
        $project = Projects::find($id);

        $validateUser = $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // Find the user by email
        $user = User::where('email', $validateUser['email'])->first();
        $role = json_decode($user->role)[0];

        if($role=='user'){
            $user->role = ["project admin"];
            $user->save();
        } 
        $project->admin = $user->id;
        $project->save();
    }
}
