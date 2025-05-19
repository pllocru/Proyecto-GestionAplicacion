<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaisesSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     */
    public function run()
    {
        $paises = [
            ['nombre' => 'España', 'codigo' => 'ES', 'moneda' => 'EUR'],
            ['nombre' => 'Estados Unidos', 'codigo' => 'US', 'moneda' => 'USD'],
            ['nombre' => 'México', 'codigo' => 'MX', 'moneda' => 'MXN'],
            ['nombre' => 'Argentina', 'codigo' => 'AR', 'moneda' => 'ARS'],
            ['nombre' => 'Colombia', 'codigo' => 'CO', 'moneda' => 'COP'],
        ];

        DB::table('paises')->insert($paises);
    }
}

