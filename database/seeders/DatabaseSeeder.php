<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //create 3 user with different role
        \App\Models\User::create([
            'email'=>'S98PBH@pte.hu',
            'password'=>Hash::make('S98PBHS98PBH'),
            'role'=>json_encode(['system admin']),
        ]);
        \App\Models\User::create([
            'email'=>'abcd@pte.hu',
            'password'=>Hash::make('abcdabcd'),
            'role'=>json_encode(['system admin']),
            'created_by'=>1
        ]);
        \App\Models\User::create([
            'email'=>'CVBN123@pte.hu',
            'password'=>Hash::make('CVBN123CVBN123'),
            'role'=>json_encode(['project admin']),
            'created_by'=>1
        ]);
        \App\Models\User::create([
            'email'=>'QWER123@pte.hu',
            'password'=>Hash::make('QWER123QWER123'),
            'role'=>json_encode(['user']),
            'created_by'=>1
        ]);

        //create 2 group
        \App\Models\Groups::create([
            'name'=>'Web development',
            'created_by'=>2
        ]);
        \App\Models\Groups::create([
            'name'=>'AI 1',
            'created_by'=>1
        ]);

        //create 2 project
        \App\Models\Projects::create([
            'name'=>'Math exam',
            'created_by'=>2
        ]);
        \App\Models\Projects::create([
            'name'=>'C++ exam',
            'created_by'=>1
        ]);
    }
}
