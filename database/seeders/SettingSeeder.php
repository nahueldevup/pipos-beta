<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // Opción A: Usar el método helper del modelo (recomendado para producción/setup inicial)
        Setting::firstOrCreate(
            ['group' => 'general', 'name' => 'general'],
            [
                'locked' => false,
                'payload' => Setting::defaultGeneralPayload(),
            ]
        );

        // Opción B: Si quisieras crear datos de prueba aleatorios adicionales
        // Setting::factory()->count(5)->create();
    }
}
