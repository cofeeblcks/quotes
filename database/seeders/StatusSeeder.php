<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create([
            'name' => 'Registrado',
            'color' => '#f39c12'
        ]);

        Status::create([
            'name' => 'Aprobado',
            'color' => '#27ae60'
        ]);

        Status::create([
            'name' => 'No aprobada',
            'color' => '#c0392b'
        ]);
    }
}
