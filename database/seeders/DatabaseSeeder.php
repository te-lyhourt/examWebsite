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
            'email'=>'CVBN123@pte.hu',
            'password'=>Hash::make('CVBN123CVBN123'),
            'role'=>json_encode(['project admin']),
        ]);
        \App\Models\User::create([
            'email'=>'QWER123@pte.hu',
            'password'=>Hash::make('QWER123QWER123'),
            'role'=>json_encode(['user']),
        ]);
    }
}
