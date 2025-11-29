<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TutoriaEquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $claves = ['ICI', 'IME', 'ICO', 'IEL', 'ISES', 'IIA'];
            $equipos = [];

            $licenciaturas = DB::table('licenciaturas')->whereIn('clave', $claves)->get();

            if ($licenciaturas->count() != count($claves)) {
                $foundClaves = $licenciaturas->pluck('clave')->toArray();
                $missingClaves = array_diff($claves, $foundClaves);
            }

            foreach ($licenciaturas as $licenciatura) {
                $equipos[] = [
                    'nombre' => "Equipo Colaborativo {$licenciatura->clave}",
                    'colaborativo' => true,
                    'lienciatura_id' => $licenciatura->id
                ];

                $equipos[] = [
                    'nombre' => "Equipo Individual {$licenciatura->clave}",
                    'colaborativo' => false,
                    'lienciatura_id' => $licenciatura->id
                ];
            }

            if (!empty($equipos)) {
                DB::table('tutoria_equipos')->insert($equipos);
                $this->command->info('tutoria_equipos table seeded');
            } else {
                $this->command->error('tutoria_equipos table could not be seeded');
            }
        } catch (Exception $e) {
            $this->command->error('Error seeding tutoria_equipos table: ' . $e->getMessage());
        }
    }
}
