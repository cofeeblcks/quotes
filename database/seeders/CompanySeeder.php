<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'Mabel Villafañe',
            'phone' => '(+57) 315 3949867',
            'email' => 'mavico054@gmail.com',
            'address' => 'Transversal 37 38-02 B La Peninsula',
            'signature_path' => 'images/signature.png',
            'logo_path' => 'images/logoColor.png',
        ]);
    }
}
