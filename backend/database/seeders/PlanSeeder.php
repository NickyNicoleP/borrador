<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('planes')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::table('planes')->insert([
            [
                'nombre' => 'Plan Basico Prepago',
                'tipo_servicio' => 'prepago',
                'datos_gb' => 5,
                'minutos' => 200,
                'sms' => 200,
                'precio_mensual' => 9.99,
                'descripcion' => 'Datos y minutos suficientes para uso ligero.',
                'activo' => true,
            ],
            [
                'nombre' => 'Plan Ilimitado Pospago',
                'tipo_servicio' => 'pospago',
                'datos_gb' => 50,
                'minutos' => 5000,
                'sms' => 3000,
                'precio_mensual' => 24.99,
                'descripcion' => 'Datos generosos y minutos para usuarios intensivos.',
                'activo' => true,
            ],
            [
                'nombre' => 'Plan Empresas',
                'tipo_servicio' => 'pospago',
                'datos_gb' => 200,
                'minutos' => 20000,
                'sms' => 20000,
                'precio_mensual' => 89.99,
                'descripcion' => 'Enfocado en equipos de trabajo con alto consumo.',
                'activo' => true,
            ],
        ]);
    }
}
