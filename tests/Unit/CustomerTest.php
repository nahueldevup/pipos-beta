<?php

use App\Models\Customer;

test('puede crear un cliente', function () {
    $customer = Customer::create([
        'name' => 'Juan Pérez',
        'phone' => '123456789',
    ]);

    expect($customer->id)->not->toBeNull();
    expect($customer->name)->toBe('Juan Pérez');
    expect($customer->phone)->toBe('123456789');
});