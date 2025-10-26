<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiPeruService;

class ConsultaPageController extends Controller
{
    public function show()
    {
        return view('consulta-doc');
    }

    public function consultarDni(Request $request, ApiPeruService $api)
    {
        $data = $request->validate(['dni' => ['required', 'digits:8']]);
        try {
            $resultado = $api->dni($data['dni']);
            return view('consulta-doc', [
                'dni' => $data['dni'],
                'dniResultado' => $resultado
            ]);
        } catch (\Throwable $e) {
            return view('consulta-doc', [
                'dni' => $data['dni'],
                'dniError' => $e->getMessage()
            ]);
        }
    }

    public function consultarRuc(Request $request, ApiPeruService $api)
    {
        $data = $request->validate(['ruc' => ['required', 'digits:11']]);
        try {
            $resultado = $api->ruc($data['ruc']);
            return view('consulta-doc', [
                'ruc' => $data['ruc'],
                'rucResultado' => $resultado
            ]);
        } catch (\Throwable $e) {
            return view('consulta-doc', [
                'ruc' => $data['ruc'],
                'rucError' => $e->getMessage()
            ]);
        }
    }
}
