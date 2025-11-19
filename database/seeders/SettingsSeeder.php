<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar si ya existe
        $exists = SystemSetting::where('group', 'general')
            ->where('name', 'general')
            ->exists();

        if (!$exists) {
            $settings = [
                'company_name' => 'Mi Empresa',
                'company_address' => '',
                'company_phone' => '',
                'tax_rate' => 0.0,
                'receipt_message' => '¡Gracias por su compra!',
                'low_stock_alert' => 5,
                'theme' => 'light',
            ];

            SystemSetting::create([
                'group' => 'general',
                'name' => 'general',
                'locked' => false,
                'payload' => $settings,
            ]);
        }
    }
}
