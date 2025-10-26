<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cotizacion;


Route::redirect('/', '/admin');

Route::get('/cotizaciones/{cotizacion}/preview', function (Cotizacion $cotizacion) {
    $pdf = Pdf::loadView('pdf.cotizacion', ['cotizacion' => $cotizacion])->setPaper('a4');

    return response($pdf->output(), 200, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => "inline; filename=Cotizacion-{$cotizacion->id}.pdf",
    ]);
})->middleware(['auth', 'signed'])  // ajusta tus middlewares
  ->name('cotizaciones.preview');


// Rutas para la consulta de DNI vía formulario web
use App\Http\Controllers\ConsultaPageController;

Route::get('/consulta-doc', [ConsultaPageController::class, 'show'])->name('consulta.doc');
Route::post('/consulta-doc/dni', [ConsultaPageController::class, 'consultarDni'])->name('consulta.doc.dni');
Route::post('/consulta-doc/ruc', [ConsultaPageController::class, 'consultarRuc'])->name('consulta.doc.ruc');


  