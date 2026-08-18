<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Entrega;

class EntregaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entregas = [
            ['nombre'=>'Entrega 1 - Propuesta', 'fecha_entrega'=>'2026-04-30',],
            ['nombre'=>'Entrega 2 - Frontend', 'fecha_entrega'=>'2026-06-11',],
            ['nombre'=>'Entrega 3 - Persistencia', 'fecha_entrega'=>'2026-07-16',],
            ['nombre'=>'Entrega 4 - Entrega Final', 'fecha_entrega'=>'2026-10-22',],
        ];

        foreach ($entregas as $entrega) {
            Entrega::createIfNotExists($entrega);
        }
    }
}
