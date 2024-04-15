<?php

namespace App\Http\Controllers;

use App\Models\Projects;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;
use Illuminate\Support\Facades\Storage;

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

            $projects = Projects::whereJsonContains('admin', $user->email)->orWhere('created_by', $user->id)->orderBy('created_at', 'desc')->get();
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
            'questNum' => 'integer',
            'repeatNum' => 'integer',
            'created_by' => 'required|exists:users,id',
            'url'
        ]);
        //create group
        $project = Projects::create([
            'name' => $validator['name'],
            'description' => $request->description,
            'questNum' => $validator['questNum'],
            'repeatNum' => $validator['repeatNum'],
            'created_by' => $validator['created_by'],
        ]);

        Storage::disk('google')->makeDirectory((string)$project->id);
        $url = Storage::disk('google')->url((string)$project->id);
        $project->url = $url;

        $project->save();
    }

    //project detial page
    public function projectDetail(Request $request)
    {
        $request->merge(['id' => $request->route('id')]);

        $validator = $request->validate([
            'id' => 'required|integer|exists:projects,id'
        ]);
        $project = Projects::findOrFail($validator['id']);
        $project = $project->load(['questions' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'groups']);

        return Inertia('Project/ProjectDetail', [
            'project' => $project
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
        // Find the group
        $project = Projects::find($id);

        // Detach users from the group
        $project->groups()->detach($request->groups);
    }

    //delete project
    public function delete(Request $request)
    {
        $validator = $request->validate([
            //validataion rule
            'projects.*' => 'required|integer|exists:projects,id'
        ]);
        Projects::whereIn('id', $validator['projects'])->delete();
    }

    public function addAdmin(Request $request, $id)
    {
        $validatUser = $request->validate(
            //validataion rule
            [
                'users.*' => 'required|email|exists:users,email',
            ],
            //custome error messsage
            [
                'users.*.required' => 'Error: email is required.',
                'users.*.email' => 'Error: :input must be a valid email address.',
                'users.*.exists' => 'Error: :input does not exist.',
            ]
        );
        $project = Projects::find($id);
        $project->admin = $validatUser['users'];
        $project->save();

        //change user role from user to project admin if needed
        $users = User::whereIn('email', $validatUser['users'])->get();
        foreach ($users as $user) {
            $role = json_decode($user->role)[0];
            if ($role == 'user') {
                $user->role = ["project admin"];
                $user->save();
            }
        }
    }
    public function removeAdmin(Request $request, $id)
    {
        // Validate the request data if needed

        // Find the group
        $project = Projects::find($id);
        $project->admin = $request->admins;
        $project->save();
    }
    //test page
    public function testPage(Request $request)
    {
        $request->merge(['id' => $request->route('id')]);

        $validator = $request->validate([
            'id' => 'required|integer|exists:projects,id'
        ]);
        $project = Projects::findOrFail($validator['id']);

        $answersCount = 0;

        //if admin limit number of question
        if ($project->questNum != null) {
            $numQuestions = $project->questNum;
            $user = Auth::user();

            $totalQuestions = $project->questions()->count();
            if ($totalQuestions > 0) {
                $offset = ((int)$user->id * $numQuestions) % $totalQuestions; // Calculate offset with wrapping around
                if ($numQuestions > $totalQuestions) $offset = 0;

                $project = $project->load(
                    [
                        'questions' => function ($query) use ($user, $numQuestions, $offset) {
                            $query->with(
                                ['answers' => function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                }]
                            )->offset($offset)->limit($numQuestions);
                        }
                    ]
                );
            }



            if ($project->repeatNum) {
                $repeatNum = $project->repeatNum;
                $count = $project->questions->count();
                // Initialize an empty array to store repeated questions
                $repeatQ = [];
                for ($j = 0; $j < $count; $j++) {
                    $answersCount = $answersCount + count($project->questions[$j]->answers);

                    //add the same question multiple times
                    for ($i = 0; $i < $repeatNum; $i++) {
                        // Add the question to the repeated questions array
                        $additionalData = ["repeatIndex" => $i];
                        // Merge the original question data with the additional data
                        $repeatQ[] = array_merge($project->questions[$j]->toArray(), $additionalData);
                    }
                }
                //overwrite relationship and data
                $project->setRelation('questions', $repeatQ);
                $project->questions = $repeatQ;
            }
        }
        //if no limit number of question 
        else {

            $project = $project->load(['questions.answers' => function ($query) {
                $user = Auth::user();
                $query->where('user_id', $user->id);
            }]);


            if ($project->repeatNum) {
                $repeatNum = $project->repeatNum;
                $count = $project->questions->count();
                // Initialize an empty array to store repeated questions
                $repeatQ = [];
                for ($j = 0; $j < $count; $j++) {
                    $answersCount = $answersCount + count($project->questions[$j]->answers);

                    $question = $project->questions[$j];
                    $answerList = $question->answers;
                    //add the same question multiple times
                    for ($i = 0; $i < $repeatNum; $i++) {
                        $answer = $answerList->filter(function ($answer) use ($i) {
                            return $answer->repeatIndex == $i;
                        })->values()->all();
                        //overwrite relationship and data
                        $question->setRelation('answers', $answer);
                        $question->answers = $answer;
                        $question->repeatIndex = $i;
                        // Add the question to the repeated questions array
                        $repeatQ[] = $question;
                    }
                }
                //overwrite relationship and data
                $project->setRelation('questions', $repeatQ);
                $project->questions = $repeatQ;
            }
        }
        $project->totalQuestions = count($project->questions);
        $project->answersCount = $answersCount;
        return Inertia('Project/testPage', [
            //load project with questions and answer
            'project' => $project
        ]);
    }
}
