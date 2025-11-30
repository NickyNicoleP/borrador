<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PromocionSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('promociones')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('promociones')->insert([
            [
                'nombre' => 'Doble de Datos',
                'descripcion' => 'Obten el doble de datos en cualquier plan por 6 meses',
                'precio' => 0,
                'vigencia' => Carbon::create(2023, 12, 31),
                'activa' => true,
                'created_at' => now(),
            ],
            [
                'nombre' => 'Plan Familiar',
                'descripcion' => 'Descuento del 20% para planes familiares con 3+ lineas',
                'precio' => 0,
                'vigencia' => Carbon::create(2023, 11, 30),
                'activa' => true,
                'created_at' => now(),
            ],
            [
                'nombre' => 'Portabilidad Gratis',
                'descripcion' => 'Cambia de compania y no pagues cargo de activacion',
                'precio' => 0,
                'vigencia' => Carbon::create(2023, 10, 15),
                'activa' => false,
                'created_at' => now(),
            ],
        ]);
    }
}
