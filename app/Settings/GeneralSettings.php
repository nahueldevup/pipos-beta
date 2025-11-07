<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $company_name = 'Mi Empresa';
    public ?string $company_address = '';        // ← Cambiado a ?string
    public ?string $company_phone = '';          // ← Cambiado a ?string
    public float $tax_rate = 0.0;
    public ?string $receipt_message = '¡Gracias por su compra!';  // ← Cambiado a ?string
    public int $low_stock_alert = 5;
    public string $theme = 'light';

    public static function group(): string
    {
        return 'general';
    }
}
