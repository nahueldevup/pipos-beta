<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    /**
     * Muestra el formulario de configuración
     */
    public function index()
    {
        // Usamos el nuevo método del modelo Setting
        $settings = Setting::getGeneral();

        return view('settings.index', [
            'settings' => $settings
        ]);
    }

    /**
     * Actualiza las configuraciones del sistema
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:20',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'receipt_message' => 'nullable|string',
            'low_stock_alert' => 'required|integer|min:0',
            'theme' => 'required|in:light,dark',
        ]);

        // Convertir campos vacíos a string vacío
        $validated['company_address'] = $validated['company_address'] ?? '';
        $validated['company_phone'] = $validated['company_phone'] ?? '';
        $validated['receipt_message'] = $validated['receipt_message'] ?? '';

        // Convertir tax_rate a float
        $validated['tax_rate'] = (float) $validated['tax_rate'];

        // Usamos el nuevo método de actualización del modelo Setting
        Setting::updateGeneral($validated);

        return redirect()->route('settings.index')
            ->with('success', 'Configuraciones actualizadas exitosamente.');
    }
}