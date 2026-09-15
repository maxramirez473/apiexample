<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Alumno;

class AlumnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alumnos = [
            [
                "legajo"=>21433,
                "nombres"=>"RAUL ANTONIO",
                "apellidos"=>"SERRA",
                "grupo_id"=>7,
            ],
            [
                "legajo"=>27628,
                "nombres"=>"AXEL GABRIEL",
                "apellidos"=>"MONZON",
                "grupo_id"=>5,
            ],
            [
                "legajo"=>31177,
                "nombres"=>"LUCIANO JORGE",
                "apellidos"=>"PIGLIAPOCO",
                "grupo_id"=>5,
            ],
            [
                "legajo"=>31195,
                "nombres"=>"DANA",
                "apellidos"=>"BALBUENA",
                "grupo_id"=>6,
            ],
            [
                "legajo"=>31217,
                "nombres"=>"JAVIER",
                "apellidos"=>"ENCISO REGALADO",
                "grupo_id"=>3,
            ],
            [
                "legajo"=>31493,
                "nombres"=>"RODRIGO NICOLAS",
                "apellidos"=>"RIAL",
                "grupo_id"=>6,
            ],
            [
                "legajo"=>32177,
                "nombres"=>"JUAN CRUZ",
                "apellidos"=>"HILLAR",
                "grupo_id"=>1,
            ],
            [
                "legajo"=>31601,
                "nombres"=>"JUAN MANUEL",
                "apellidos"=>"MARTINEZ",
                "grupo_id"=>2,
            ],
            [
                "legajo"=>32232,
                "nombres"=>"ALEXIS JONATHAN",
                "apellidos"=>"ALEGRE",
                "grupo_id"=>7,
            ],
            [
                "legajo"=>32368,
                "nombres"=>"JERONIMO",
                "apellidos"=>"LLANOS BOSCATO",
                "grupo_id"=>2,
            ],
            [
                "legajo"=>32492,
                "nombres"=>"GONZALO DAMIAN",
                "apellidos"=>"LORENZO",
            ],
            [
                "legajo"=>32535,
                "nombres"=>"LEANDRO EZEQUIEL",
                "apellidos"=>"MUGETTI",
                "grupo_id"=>3,
            ],
            [
                "legajo"=>33065,
                "nombres"=>"CIRO",
                "apellidos"=>"CENTURION NAVARRO",
                "grupo_id"=>8,
            ],
            [
                "legajo"=>33523,
                "nombres"=>"MAURO FELIPE",
                "apellidos"=>"CASALE",
                "grupo_id"=>9,
            ],
            [
                "legajo"=>33592,
                "nombres"=>"NICOLAS",
                "apellidos"=>"LEGUIZAMON",
                "grupo_id"=>4,
            ],
            [
                "legajo"=>33605,
                "nombres"=>"IGNACIO",
                "apellidos"=>"PAGOTTO",
                "grupo_id"=>4,
            ],
            [
                "legajo"=>33621,
                "nombres"=>"JUSTINA",
                "apellidos"=>"LOZANO",
                "grupo_id"=>8,
            ],
            [
                "legajo"=>33637,
                "nombres"=>"NICOLAS",
                "apellidos"=>"YORLANO",
                "grupo_id"=>9,
            ],
            [
                "legajo"=>33647,
                "nombres"=>"JOSE",
                "apellidos"=>"JOAQUIN SANTORO",
                "grupo_id"=>4,
            ],
            [
                "legajo"=>33789,
                "nombres"=>"MARTIN",
                "apellidos"=>"SANDER",
                "grupo_id"=>2,
            ],
            [
                "legajo"=>34095,
                "nombres"=>"GIANLUCA JOAQUIN",
                "apellidos"=>"BAIZA",
                "grupo_id"=>1,
            ],
            [
                "legajo"=>34191,
                "nombres"=>"SANTIAGO JESÚS",
                "apellidos"=>"ORTIZ",
                "grupo_id"=>7,
            ],
            [
                "legajo"=>34261,
                "nombres"=>"THIAGO MAURICIO",
                "apellidos"=>"VIAZZI",
                "grupo_id"=>10,
            ],
            [
                "legajo"=>34270,
                "nombres"=>"VALENTINA MORENA",
                "apellidos"=>"VILLALBA",
                "grupo_id"=>10,
            ],
            [
                "legajo"=>34321,
                "nombres"=>"LAUTARO",
                "apellidos"=>"REY",
                "grupo_id"=>6,
            ],
            [
                "legajo"=>34323,
                "nombres"=>"PABLO",
                "apellidos"=>"ALESSANDRINI FLORES",
                "grupo_id"=>9,
            ],
            [
                "legajo"=>34336,
                "nombres"=>"LUZ SOFIA",
                "apellidos"=>"JULIAN",
                "grupo_id"=>10,
            ],
            [
                "legajo"=>34356,
                "nombres"=>"STEFANO LAUTARO",
                "apellidos"=>"DIAZ",
                "grupo_id"=>1,
            ],
            [
                "legajo"=>34480,
                "nombres"=>"SANTIAGO",
                "apellidos"=>"BALLARIO",
                "grupo_id"=>1,
            ],
            [
                "legajo"=>34485,
                "nombres"=>"LEANDRO GABRIEL",
                "apellidos"=>"ROCA",
                "grupo_id"=>3,
            ],
            [
                "legajo"=>34498,
                "nombres"=>"ALEJO EZEQUIEL",
                "apellidos"=>"ALDREY",
                "grupo_id"=>3,
            ],
            [
                "legajo"=>34573,
                "nombres"=>"JUAN PABLO",
                "apellidos"=>"QUIROZ MALLON ",
                "grupo_id"=>6,
            ],
            [
                "legajo"=>34589,
                "nombres"=>"MORENA",
                "apellidos"=>"MANCINELLO",
                "grupo_id"=>10,
            ],
            [
                "legajo"=>34650,
                "nombres"=>"TOMAS AGUSTIN",
                "apellidos"=>"ARBER ARAMBURU",
                "grupo_id"=>4,
            ],
            [
                "legajo"=>24714,
                "nombres"=>"GRACE SABBATH",
                "apellidos"=>"RUBIO GUERRERO",
            ],
            [
                "legajo"=>32606,
                "nombres"=>"LISANDRO RAMIRO",
                "apellidos"=>"LONCON",
            ],
            [
                "legajo"=>33137,
                "nombres"=>"IGNACIO ESTEBAN",
                "apellidos"=>"RENDANI",
                "grupo_id"=>5,
            ],
            [
                "legajo"=>33174,
                "nombres"=>"TOMAS",
                "apellidos"=>"HERRERA",
                "grupo_id"=>5,
            ],
            [
                "legajo"=>33458,
                "nombres"=>"LUCIO",
                "apellidos"=>"CANTALUPO",
                "grupo_id"=>8,
            ],
        ];

        foreach ($alumnos as $alumno) {
            Alumno::create($alumno);
        }
    }
}
