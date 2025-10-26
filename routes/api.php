<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DniLookupController;

// ✅ Ruta POST “real” (para Postman/JS)
Route::post('/lookup/dni', [DniLookupController::class, 'lookup'])->name('api.lookup.dni');

// ✅ Ruta GET de prueba (para navegar en el browser)
Route::get('/lookup/dni', function (\Illuminate\Http\Request $request) {
    $dni = $request->query('dni');

    if (!preg_match('/^\d{8}$/', (string) $dni)) {
        return response()->json([
            'success' => false,
            'message' => 'Proporciona ?dni=XXXXXXXX (8 dígitos).',
        ], 422);
    }

    /** @var \App\Services\ApiPeruService $api */
    $api = app(\App\Services\ApiPeruService::class);

    try {
        $resp = $api->dni($dni);
        return response()->json($resp, 200);
    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 422);
    }
})->name('api.lookup.dni.get');
