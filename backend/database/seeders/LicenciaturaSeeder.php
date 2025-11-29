<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LicenciaturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('licenciaturas')->insert([
            ['clave' => 'ICI', 'nombre' => 'Ingeniería Civil'],
            ['clave' => 'IME', 'nombre' => 'Ingeniería Mecánica'],
            ['clave' => 'ICO', 'nombre' => 'Ingeniería en Computación'],
            ['clave' => 'IEL', 'nombre' => 'Ingeniería Electrónica'],
            ['clave' => 'ISES', 'nombre' => 'Ingeniería en Sistemas Energéticos Sustentables'],
            ['clave' => 'IIA', 'nombre' => 'Ingeniería en Inteligencia Artificial']
        ]);
    }
}
