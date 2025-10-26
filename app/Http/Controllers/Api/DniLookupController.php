<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiPeruService;

class DniPageController extends Controller
{
    public function show()
    {
        // Página vacía al cargar
        return view('consulta-dni');
    }

    public function consultar(Request $request, ApiPeruService $api)
    {
        $data = $request->validate([
            'dni' => ['required', 'digits:8'],
        ], [
            'dni.required' => 'Ingresa un DNI.',
            'dni.digits'   => 'El DNI debe tener 8 dígitos.',
        ]);

        $resultado = null;
        $error = null;

        try {
            $resultado = $api->dni($data['dni']); // Llama a tu servicio
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }

        return view('consulta-dni', [
            'dni'       => $data['dni'],
            'resultado' => $resultado,
            'error'     => $error,
        ]);
    }
}
