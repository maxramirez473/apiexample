<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grupo;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grupos = [
            [
                'id' => 1,
                'nombre' => 'Eflet',
                'tp_integrador' => 'Logística de Distribución Farmacéutica.',
            ],
            [
                'id' => 2,
                'nombre' => 'Gestión de Canchas',
                'tp_integrador' => 'Gestión Deportiva.',
            ],
            [
                'id' => 3,
                'nombre' => 'StateFlow',
                'tp_integrador' => 'Sistema de Trazabilidad Industrial y Control de Calidad.',
            ],
            [
                'id' => 4,
                'nombre' => 'Monu Burguer',
                'tp_integrador' => 'Gestión de Pedidos y Ventas para Restaurantes.',
            ],
            [
                'id' => 5,
                'nombre' => 'AdoptMascota',
                'tp_integrador' => 'Gestión de refugio de animales',
            ],
            [
                'id' => 6,
                'nombre' => 'MiTecnico',
                'tp_integrador' => 'Sistema de Intermediación de Servicios Técnicos Verificados.',
            ],
            [
                'id' => 7,
                'nombre' => 'RentFlow',
                'tp_integrador' => 'Gestión Administrativa y Financiera Inmobiliaria.',
            ],
            [
                'id' => 8,
                'nombre' => 'Salux',
                'tp_integrador' => 'Gestión de Turnos Medios Accesibles.',
            ],
            [
                'id' => 9,
                'nombre' => 'Scaps',
                'tp_integrador' => 'E-comerce de nicho.',
            ],
            [
                'id' => 10,
                'nombre' => 'Veo Tu Micro',
                'tp_integrador' => 'Gestión de abordaje de autobús público para personas con discapacidad visual.',
            ],
        ];

        foreach ($grupos as $grupo) {
            Grupo::create($grupo);
        }
    }
}
