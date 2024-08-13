<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\AcademicProgram;
use App\Models\AcademicSchool;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Mabel Villafañe',
            'phone' => '3153949867',
            'email' => 'mavico054@gmail.com',
            'password' => Hash::make('passw0rd'),
        ]);
    }
}
