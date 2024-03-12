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
            'admin' => 1
        ]);
        \App\Models\Projects::create([
            'name' => 'C++ exam',
            'created_by' => 1,
            'admin' => 3
        ]);

        \App\Models\Projects::create([
            'name' => 'Math exam 2',
            'created_by' => 2,
            'admin' => 3
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
    }
}
