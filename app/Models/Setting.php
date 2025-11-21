<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    // Por convención, el modelo Setting buscará la tabla 'settings',
    // pero lo dejamos explícito por claridad.
    protected $table = 'settings';

    protected $fillable = [
        'group',
        'name',
        'locked',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'locked' => 'boolean',
    ];

    /**
     * Helper para obtener configuraciones generales con caché
     */
    public static function getGeneral()
    {
        return Cache::remember('settings.general', 3600, function () {
            $setting = self::firstOrCreate(
                ['group' => 'general', 'name' => 'general'],
                [
                    'locked' => false,
                    'payload' => self::defaultGeneralPayload(),
                ]
            );

            return (object) $setting->payload;
        });
    }

    /**
     * Helper para actualizar configuraciones generales
     */
    public static function updateGeneral(array $data)
    {
        $setting = self::updateOrCreate(
            ['group' => 'general', 'name' => 'general'],
            [
                'locked' => false,
                'payload' => $data,
            ]
        );

        Cache::forget('settings.general');

        return $setting;
    }

    /**
     * Define los valores por defecto para evitar repetición
     */
    public static function defaultGeneralPayload(): array
    {
        return [
            'company_name' => 'Mi Empresa',
            'company_address' => '',
            'company_phone' => '',
            'tax_rate' => 0.0,
            'receipt_message' => '¡Gracias por su compra!',
            'low_stock_alert' => 5,
            'theme' => 'light',
        ];
    }
}
