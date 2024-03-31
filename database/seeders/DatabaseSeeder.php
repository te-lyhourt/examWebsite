<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\Groups;
use App\Models\Projects;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //create 3 user with different role
        \App\Models\User::create([
            'email' => 'S98PBH@pte.hu',
            'password' => Hash::make('S98PBHS98PBH'),
            'role' => json_encode(['system admin']),
        ]);
        \App\Models\User::create([
            'email' => 'system@pte.hu',
            'password' => Hash::make('systemsystem'),
            'role' => json_encode(['system admin']),
            'created_by' => 1
        ]);
        \App\Models\User::create([
            'email' => 'project@pte.hu',
            'password' => Hash::make('projectproject'),
            'role' => json_encode(['project admin']),
            'created_by' => 1
        ]);
        \App\Models\User::create([
            'email' => 'user@pte.hu',
            'password' => Hash::make('useruser'),
            'role' => json_encode(['user']),
            'created_by' => 1
        ]);

        //create 4 group
        \App\Models\Groups::create([
            'name' => 'Web development',
            'created_by' => 2
        ]);
        \App\Models\Groups::create([
            'name' => 'software development',
            'created_by' => 1
        ]);
        \App\Models\Groups::create([
            'name' => 'AI 1',
            'created_by' => 1
        ]);
        \App\Models\Groups::create([
            'name' => 'AI 2',
            'created_by' => 1
        ]);

        //create 3 project
        \App\Models\Projects::create([
            'name' => 'Math exam',
            'created_by' => 3,
            'admin' => json_encode(["S98PBH@pte.hu","project@pte.hu"])
        ]);
        \App\Models\Projects::create([
            'name' => 'C++ exam',
            'created_by' => 1,
            'admin' => json_encode("project@pte.hu")
        ]);

        \App\Models\Projects::create([
            'name' => 'Math exam 2',
            'created_by' => 2,
            'admin' => json_encode(["S98PBH@pte.hu","project@pte.hu"])
        ]);

        //add 3 user into group 1, user4 into group 2 3
        $group = Groups::find(1);
        $users = [
            1 => ["added_by" => 1],
            3 => ["added_by" => 1],
            4
        ];
        $group->users()->syncWithoutDetaching($users);
        $group = Groups::find(2);
        $users = [4];
        $group->users()->syncWithoutDetaching($users);
        $group = Groups::find(3);
        $users = [4];
        $group->users()->syncWithoutDetaching($users);

        //add 3 gourp into project 1 2 3
        $project = Projects::find(1);
        $groups = [
            1 => ["added_by" => 1],
            2,
            3,
        ];
        $project->groups()->syncWithoutDetaching($groups);

        $project = Projects::find(2);
        $groups = [
            1 => ["added_by" => 1],
            2,
            3,
        ];
        $project->groups()->syncWithoutDetaching($groups);

        $project = Projects::find(3);
        $groups = [
            1 => ["added_by" => 1],
            2,
            3,
        ];

        $project->groups()->syncWithoutDetaching($groups);

        //create question
        \App\Models\Questions::create([
            'description' => 'what is 1+1',
            'type' => 'default',
            'fileUpload'=>'none',
            'projects_id'=>1
        ]);
        \App\Models\Questions::create([
            'description' => 'what is speedrun',
            'type' => 'default',
            'fileUpload'=>'upFile',
            'projects_id'=>1
        ]);
        \App\Models\Questions::create([
            'description' => 'what is water',
            'type' => 'default',
            'fileUpload'=>'upAudio',
            'projects_id'=>1
        ]);
        \App\Models\Questions::create([
            'description' => 'what is 1+2',
            'type' => 'multiple',
            'options'=>json_encode([
                0,
                1,
                2,
                3,
            ]),
            'projects_id'=>1
        ]);
        \App\Models\Questions::create([
            'description' => 'what is 2+2',
            'type' => 'checkbox',
            'options'=>json_encode([
                0,
                1,
                2,
                3
            ]),
            'projects_id'=>1
        ]);
        \App\Models\Questions::create([
            'description' => 'select color',
            'type' => 'checkbox',
            'options'=>json_encode([
                "red",
                "blue",
                "green",
                3
            ]),
            'projects_id'=>1
        ]);
        \App\Models\Questions::create([
            'description' => 'select color',
            'type' => 'multiple',
            'options'=>json_encode([
                0,
                "green",
                2,
                3,
            ]),
            'projects_id'=>1
        ]);
    }
}
