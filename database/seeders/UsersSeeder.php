<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'email'=>'S98PBH@pte.hu',
            'password'=>Hash::make('S98PBHS98PBH'),
            'role'=>'system admin',
        ]);
        \App\Models\User::create([
            'email'=>'CVBN123@pte.hu',
            'password'=>Hash::make('CVBN123CVBN123'),
            'role'=>'project admin',
        ]);
        \App\Models\User::create([
            'email'=>'QWER123@pte.hu',
            'password'=>Hash::make('QWER123QWER123'),
            'role'=>'user',
        ]);
    }
}
