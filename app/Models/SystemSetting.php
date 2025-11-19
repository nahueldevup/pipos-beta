<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'group',
        'name',
        'locked',
        'payload'
    ];

    protected $casts = [
        'payload' => 'array',
        'locked' => 'boolean'
    ];

    /**
     * Obtener todas las configuraciones como un objeto
     */
    public static function getSettings()
    {
        return Cache::remember('system_settings', 3600, function () {
            $setting = self::where('group', 'general')
                ->where('name', 'general')
                ->first();

            if (!$setting) {
                // Crear configuración por defecto si no existe
                $defaultPayload = [
                    'company_name' => 'Mi Empresa',
                    'company_address' => '',
                    'company_phone' => '',
                    'tax_rate' => 0.0,
                    'receipt_message' => '¡Gracias por su compra!',
                    'low_stock_alert' => 5,
                    'theme' => 'light',
                ];

                $setting = self::create([
                    'group' => 'general',
                    'name' => 'general',
                    'locked' => false,
                    'payload' => $defaultPayload
                ]);
            }

            return (object) $setting->payload;
        });
    }

    /**
     * Actualizar las configuraciones
     */
    public static function updateSettings(array $data)
    {
        $setting = self::where('group', 'general')
            ->where('name', 'general')
            ->first();

        if ($setting) {
            $setting->update(['payload' => $data]);
        } else {
            self::create([
                'group' => 'general',
                'name' => 'general',
                'locked' => false,
                'payload' => $data
            ]);
        }

        // Limpiar caché
        Cache::forget('system_settings');

        return true;
    }
}
