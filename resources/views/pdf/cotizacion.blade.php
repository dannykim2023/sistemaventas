<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización #{{ $cotizacion->id }}</title>
    <style>
        @page {
            margin: 25px 30px; /* márgenes globales del PDF */
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        /* Header Azul */
        .header {
            background: #0040ff;
            color: #fff;
            padding: 15px;
            border-radius: 6px;
        }
        .header table { width: 100%; }
        .header td { vertical-align: top; font-size: 11px; }
        .header .title {
            font-size: 19px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: right;
        }
        .header strong { font-size: 12px; }

        /* Métodos de pago */
        .pagos {
            margin: 20px 0;
            font-size: 11px;
        }
        .pagos h4 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 6px;
            color: #0040ff;
        }
        .pagos table { width: 100%; }
        .pagos td {
            padding: 4px 6px;
            vertical-align: top;
            font-size: 11px;
        }
        .pagos strong { font-size: 11px; }

        /* Tabla productos */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items th {
            background: #0040ff;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
            padding: 8px;
            border: 1px solid #ddd;
        }
        .items td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        /* Totales */
        .totales {
            width: 40%;
            float: right;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .totales td {
            padding: 6px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        .totales tr td:first-child {
            font-weight: bold;
            text-align: right;
        }
        .totales tr:last-child {
            background: #0040ff;
            color: #fff;
            font-weight: bold;
            font-size: 12px;
        }

        /* Detalles */
        .details {
            font-size: 11px;
            margin-top: 30px;
            line-height: 1.4;
        }
        .details h4 {
            font-size: 12px;
            margin-bottom: 5px;
            color: #0040ff;
        }

        /* Contacto */
        .contact {
            font-size: 11px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td style="width: 50%;">
                    <img src="{{ public_path('imagenes/AgenciaDN.png') }}" alt="Logo" style="height: 60px; margin-bottom: 5px;"><br>
                    <strong>AGENCIA DN - SOFTWARE & MARKETING S.A.C.</strong><br>
                    RUC: 20641261493<br>
                    Dirección: Calle Robinson 113 - Surquillo - Lima<br>
                </td>
                <td class="title">COTIZACIÓN</td>
            </tr>
            <tr>
                <td style="padding-top: 10px;">
                    <strong>Cliente:</strong><br>
                    {{ $cotizacion->cliente->nombre }}<br>
                    {{ $cotizacion->cliente->email }}<br>
                    {{ $cotizacion->cliente->dni ?? $cotizacion->cliente->ruc }}
                </td>
                <td style="text-align: right; padding-top: 10px;">
                    <strong>C. Número:</strong> #{{ $cotizacion->id }}<br>
                    <strong>F. Vencimiento:</strong> {{ $cotizacion->fecha->addDays(7)->format('d/m/Y') }}<br>
                    <strong>F. Emisión:</strong> {{ $cotizacion->fecha->format('d/m/Y') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Métodos de pago -->
    <div class="pagos">
        <h4>MÉTODOS DE PAGO</h4>
        <table>
            <tr>
                <td><strong>YAPE/PLIN:</strong> 973 777 665</td>
                <td><strong>Cuenta Corriente Interbank:</strong> 200-3007316583</td>
            </tr>
            <tr>
                <td><strong>CUENTA BCP:</strong> 41002140899017</td>
                <td><strong>CCI:</strong> 003-200-003007316583-36</td>
            </tr>
            <tr>
                <td><strong>BCP INTERBANCARIA:</strong> 0024101024089901799</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Nombre:</strong> Lorenzo Daniel Sancho Osco</td>
            </tr>
        </table>
    </div>

    <!-- Detalles -->
    <table class="items">
        <thead>
            <tr>
                <th>Ítem</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cotizacion->detalles as $i => $detalle)
            <tr>
                <td align="center">{{ $i+1 }}</td>
                <td>{{ $detalle->producto->titulo }}</td>
                <td align="center">{{ $detalle->cantidad }}</td>
                <td align="right">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totales -->
    <table class="totales">
        <tr>
            <td>Sub-total:</td>
            <td align="right">S/ {{ number_format($cotizacion->total_sin_igv, 2) }}</td>
        </tr>
        @if (!empty($cotizacion->descuento_global) && $cotizacion->descuento_global > 0)
            <tr>
                <td>Descuento:</td>
                <td align="right">- S/ {{ number_format($cotizacion->descuento_global, 2) }}</td>
            </tr>
        @endif
        <tr>
            <td>IGV (18%):</td>
            <td align="right">
                @if ($cotizacion->igv_monto > 0)
                    S/ {{ number_format($cotizacion->igv_monto, 2) }}
                @else
                    –
                @endif
            </td>
        </tr>
        <tr>
            <td>Total:</td>
            <td align="right">S/ {{ number_format($cotizacion->total_con_igv, 2) }}</td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    <!-- Detalles adicionales -->
    <div class="details">
        <h4>Detalles Adicionales:</h4>
        <p>El proyecto inicia con un pago del 30% del total acordado. El saldo restante debe ser cancelado antes de migrar la página web al dominio principal del cliente. 
        Una vez entregado el diseño inicial, el cliente podrá solicitar cambios mínimos dentro de un plazo de 7 días hábiles después de completar el proyecto.</p>
        <p>Renovación anual: S/300. Dominio + Hosting (si aplica).</p>
    </div>

    <!-- Condiciones -->
    <div class="details">
        <h4>Condiciones:</h4>
        <p><strong>Validez:</strong> 7 días</p>
        <p><strong>Forma de Pag0:</strong> 50% anticipo y 50% contra entrega</p>
        <p><strong>Tiempo de entrega:</strong> 5 días hábiles</p>
    </div>

    <!-- Contacto -->
    <div class="contact">
        <strong>Contacto:</strong><br>
        Tel: +51 959 114 988<br>
    </div>
</body>
</html>
