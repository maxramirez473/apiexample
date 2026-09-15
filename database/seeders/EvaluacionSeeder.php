<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Evaluacion;

class EvaluacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $evaluaciones=[
            [
                'id'=>1,
                'nombre'=>'EXAMEN INTEGRADOR',
            ],
            [
                'id'=>2,
                'nombre'=>'TRABAJO GRUPAL INTEGRADOR - FECHA 1',
            ],
            [
                'id'=>3,
                'nombre'=>'TRABAJO GRUPAL INTEGRADOR - FECHA 2',
            ],
            [
                'id'=>4,
                'nombre'=>'EXAMEN INTEGRADOR -  RECUPERATORIO',
            ],
            [
                'id'=>5,
                'nombre'=>'TRABAJO GRUPAL INTEGRADOR - RECUPERATORIO',
            ],
            [
                'id'=>6,
                'nombre'=>'EXAMEN INTEGRADOR - 2° RECUPERATORIO',
            ],
            [
                'id'=>7,
                'nombre'=>'EXAMEN FLOTANTE',
            ],
        ];

        foreach ($evaluaciones as $evaluacion) {
            Evaluacion::create($evaluacion);
        }
    }
}
