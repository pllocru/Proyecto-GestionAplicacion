<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ClientsSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     */
    public function run()
    {
        $faker = Faker::create();

        // Obtener una lista de países disponibles
        $paises = DB::table('paises')->pluck('id');

        for ($i = 1; $i <= 10; $i++) {
            DB::table('clients')->insert([
                'cif' => strtoupper($faker->bothify('A########')),
                'name' => $faker->company,
                'telefono' => $faker->numerify('6########'),
                'email' => $faker->unique()->companyEmail,
                'cuenta_corriente' => $faker->iban('ES'),
                'pais_id' => $paises->random(), // Relaciona con un país aleatorio
                'moneda' => 'EUR',
                'importe_cuota_mensual' => $faker->randomFloat(2, 50, 500), // Entre 50€ y 500€
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

