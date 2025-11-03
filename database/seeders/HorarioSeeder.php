<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorarioSeeder extends Seeder
{
    public function run()
    {
        $horarios = [
            ['idHorario' => 1, 'descripcion' => 'Turno Mañana', 'horaEntrada' => '08:00:00', 'horaSalida' => '16:00:00'],
            ['idHorario' => 2, 'descripcion' => 'Turno Tarde', 'horaEntrada' => '16:00:00', 'horaSalida' => '00:00:00'],
            ['idHorario' => 3, 'descripcion' => 'Turno Noche', 'horaEntrada' => '00:00:00', 'horaSalida' => '08:00:00'],
        ];

        foreach ($horarios as $horario) {
            DB::table('horario')->insertOrIgnore($horario);
        }
    }
}
