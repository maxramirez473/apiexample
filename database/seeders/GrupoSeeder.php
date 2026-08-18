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
                'nombre' => 'Eflet',
                'tp_integrador' => 'Logística de Distribución Farmacéutica.',
            ],
            [
                'nombre' => 'Gestión de Canchas',
                'tp_integrador' => 'Gestión Deportiva.',
            ],
            [
                'nombre' => 'StateFlow',
                'tp_integrador' => 'Sistema de Trazabilidad Industrial y Control de Calidad.',
            ],
            [
                'nombre' => 'Monu Burguer',
                'tp_integrador' => 'Gestión de Pedidos y Ventas para Restaurantes.',
            ],
            [
                'nombre' => 'AdoptMascota',
                'tp_integrador' => 'Gestión de refugio de animales',
            ],
            [
                'nombre' => 'MiTecnico',
                'tp_integrador' => 'Sistema de Intermediación de Servicios Técnicos Verificados.',
            ],
            [
                'nombre' => 'RentFlow',
                'tp_integrador' => 'Gestión Administrativa y Financiera Inmobiliaria.',
            ],
            [
                'nombre' => 'Salux',
                'tp_integrador' => 'Gestión de Turnos Medios Accesibles.',
            ],
            [
                'nombre' => 'Scaps',
                'tp_integrador' => 'E-comerce de nicho.',
            ],
            [
                'nombre' => 'Veo Tu Micro',
                'tp_integrador' => 'Gestión de abordaje de autobús público para personas con discapacidad visual.',
            ],
        ];

        foreach ($grupos as $grupo) {
            Grupo::create($grupo);
        }
    }
}
