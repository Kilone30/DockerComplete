<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanEstudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plan_estudios')->insert([
            ['nombre' => 'F3', 'fecha_implementacion' => '2003-01-01'],
            ['nombre' => 'F19', 'fecha_implementacion' => '2019-01-01'],
            ['nombre' => 'F24', 'fecha_implementacion' => '2024-01-01']
        ]);
    }
}
