<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Insertar directamente en la base de datos
        $settings = [
            'company_name' => 'Mi Empresa',
            'company_address' => '',
            'company_phone' => '',
            'tax_rate' => 0.0,
            'receipt_message' => '¡Gracias por su compra!',
            'low_stock_alert' => 5,
            'theme' => 'light',
        ];

        DB::table('settings')->insert([
            'group' => 'general',
            'name' => 'general',
            'locked' => false,
            'payload' => json_encode($settings),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
