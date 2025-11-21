<?php

use App\Models\Setting;

test('puede obtener configuracion general', function () {
    $settings = Setting::getGeneral();

    expect($settings)->not->toBeNull();
    expect($settings)->toBeObject();
    expect($settings)->toHaveProperty('company_name');
    expect($settings)->toHaveProperty('tax_rate');
});

test('puede actualizar configuracion general', function () {
    $data = [
        'company_name' => 'Mi Tienda',
        'tax_rate' => 21.0,
        'company_address' => 'Calle Falsa 123',
        'company_phone' => '123456789',
        'receipt_message' => '¡Gracias!',
        'low_stock_alert' => 10,
        'theme' => 'dark',
    ];

    Setting::updateGeneral($data);

    $settings = Setting::getGeneral();
    expect($settings->company_name)->toBe('Mi Tienda');
    expect($settings->tax_rate)->toEqual(21.0);
});
