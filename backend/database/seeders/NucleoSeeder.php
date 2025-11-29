<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NucleoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('nucleos')->insert([
            ['nombre' => 'Básico'],
            ['nombre' => 'Sustantivo'],
            ['nombre' => 'Integral']
        ]);
    }
}
