<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Cotización #{{ $cotizacion->id }}</title>
  <style>
    @page { size: A4; margin: 18mm 16mm 16mm 16mm; }
    * { box-sizing: border-box; }
    html, body, table, th, td, div, span {
      font-family: "DejaVu Sans", sans-serif;
      color: #1a1a1a;
    }
    body { font-size: 13.5px; line-height: 1.55; background: #fff; }

    .muted { color: #555; }
    .strong { font-weight: 700; }
    .right { text-align: right; }
    .num { text-align: right; white-space: nowrap; }

    /* ===== Encabezado ===== */
    .header { width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 18px; }
    .header td { vertical-align: middle; }
    .logo { width: 250px; }
    .title {
      font-size: 34px;
      text-transform: uppercase;
      font-weight: 800;
      text-align: right;
      color: #000;
    }


    /* ===== Tabla de tiempos de entrega ===== */
    table.delivery-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 22px;
    margin-bottom: 8px;
    }

    .delivery-table thead th {
    background: #000;
    color: #fff;
    text-transform: uppercase;
    font-weight: 700;
    font-size: 12.5px;
    padding: 8px 10px;
    text-align: left;
    border-bottom: 2px solid #000;
    }

    .delivery-table tbody td {
    padding: 8px 10px;
    font-size: 12.5px;
    border-bottom: 1px solid #ccc;
    }

    .note-delivery {
    font-size: 12px;
    color: #333;
    margin-top: 6px;
    font-style: italic;
    }


    /* ===== Firma ===== */
    .signature-box {
      display: inline-block;
      width: 52%;             /* igual al ancho de .totals */
      text-align: center;
      margin-top: 16px;
    }
    .signature-label { font-size: 12px; color: #333; margin-bottom: 2px; }
    .signature-line { width: 100%; border-top: 1px solid #000; margin: 0 auto 8px auto; }
    .signature-name { font-weight: 700; font-size: 13.5px; }
    .signature-role { font-size: 12.5px; color: #555; }
    .signature-img { width: 110px; display: block; margin: 0 auto 6px auto; }

    /* ===== Bloque de info ===== */
    .info { width: 100%; margin-top: 6px; margin-bottom: 14px; }
    .info td { vertical-align: top; padding: 3px 0; font-size: 12.5px; }

    /* ===== Nota de pago ===== */
    .note {
      border-left: 3px solid #000;
      padding: 10px 12px;
      background: #f8f8f8;
      margin: 12px 0 16px;
      font-size: 12.5px;
    }

    /* ===== Tabla de ítems ===== */
    table.items { width: 100%; border-collapse: collapse; margin-top: 8px; }
    .items thead th {
      background: #000;
      color: #fff;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 12.5px;
      padding: 10px 9px;
      text-align: left;
      border-bottom: 2px solid #000;
    }
    .items tbody tr:nth-child(odd) { background: #f9f9f9; }
    .items tbody td {
      padding: 9px 9px;
      border-bottom: 1px solid #949494ff;
      font-size: 12.5px;
    }

    /* ===== Totales ===== */
    table.wrap-totals { width: 100%; margin-top: 18px; }
    table.totals { width: 57%; margin-left: auto; border-collapse: collapse; }
    .totals td {
      padding: 9px 10px;
      border-bottom: 1px solid #949494ff;
      font-size: 12.5px;
    }
    .totals td:first-child {
      font-weight: 700;
      text-align: right;
      width: 55%;
    }

    /* Banda TOTAL horizontal */
    .grand-row { background: #dadadaff; color: #fff; border: none; }
    .grand-box {
      display: table;
      width: 100%;
      padding: 0px;
    }
    .grand-label, .grand-amount {
      display: table-cell;
      vertical-align: middle;
      font-weight: 800;
      font-size: 16px;
    }
    .grand-label { text-align: left; }
    .grand-amount { text-align: right; }

    /* ===== Separador ===== */
    .section-divider {
      border-top: 1px solid #000;
      margin: 24px 0 12px;
    }

    /* ===== Footer ===== */
    .footer { width: 100%; font-size: 12.5px; }
    .footer td { vertical-align: top; padding-right: 12px; }
    .pill {
      display: inline-block;
      background: #000;
      color: #fff;
      padding: 6px 12px;
      border-radius: 14px;
      font-weight: 700;
      font-size: 12.5px;
    }

    /* Nota al lado de los totales */
    .totals-note {
    font-size: 12.5px;
    color: #333;
    background: #f8f8f8;
    padding: 10px 12px;
    border-left: 3px solid #000;
    line-height: 1.4;
    }


    /* ===== Salto de página ===== */
    .page-break {
      page-break-before: always;
    }
  </style>
</head>
<body>

  <!-- ===== HEADER ===== -->
  <table class="header">
    <tr>
      <td>
        <img class="logo" src="{{ public_path('imagenes/logoagenciadnHorizontal.png') }}" alt="Logo">
      </td>
      <td><div class="title">COTIZACIÓN</div></td>
    </tr>
  </table>

  <!-- ===== INFO ===== -->
  <table class="info">
    <tr>
      <td style="width: 55%; padding-right: 12px;">
        <div class="strong">Agencia DN – Software & Marketing S.A.C.</div>
        <div class="muted">
          RUC: 20641261493<br>
          Calle Robinson 113 – Surquillo – Lima<br>
          Tel: +51 959 114 988
        </div>
      </td>
      <td style="width: 45%;">
        <div class="strong">Para:</div>
        <div class="muted">
          {{ $cotizacion->cliente->nombre }}<br>
          {{ $cotizacion->cliente->email }}<br>
          {{ $cotizacion->cliente->dni ?? $cotizacion->cliente->ruc }}
        </div>
      </td>
    </tr>
    <tr>
      <td class="muted" style="padding-top: 8px;">
        <span class="strong">N° Cotización:</span> #{{ $cotizacion->id }}<br>
        <span class="strong">Fecha:</span> {{ optional($cotizacion->fecha)->format('d/m/Y') }}
      </td>
      <td class="muted" style="padding-top: 8px;">
        <span class="strong">Validez:</span> 7 días<br>
        <span class="strong">Moneda:</span> PEN (S/)
      </td>
    </tr>
  </table>

  <!-- ===== MÉTODOS DE PAGO ===== -->
  <div class="note">
    <span class="strong">Métodos de pago:</span>
    YAPE/PLIN: 973 777 665 · Interbank: 200-3007316583 · BCP: 41002140899017 ·
    CCI: 003-200-003007316583-36 · BCP Interbancaria: 0024101024089901799 ·
    Titular: Lorenzo Daniel Sancho Osco
  </div>

  <!-- ===== ITEMS ===== -->
  <table class="items">
    <thead>
      <tr>
        <th style="width: 50%;">Descripción del artículo</th>
        <th style="width: 15%;">Precio</th>
        <th style="width: 15%;">Cantidad</th>
        <th style="width: 20%;">Total</th>
      </tr>
    </thead>
    <tbody>
      @php $subtotal = 0; @endphp
      @foreach ($cotizacion->detalles as $detalle)
        @php
          $linea = ($detalle->precio_unitario ?? 0) * ($detalle->cantidad ?? 0);
          $subtotal += $linea;
        @endphp
        <tr>
          <td>
            <div class="strong">{{ $detalle->producto->titulo }}</div>
            @if(!empty($detalle->descripcion))
              <div class="muted" style="font-size:12px;">{{ $detalle->descripcion }}</div>
            @endif
          </td>
          <td class="num">S/ {{ number_format($detalle->precio_unitario, 2) }}</td>
          <td class="num">{{ number_format($detalle->cantidad, 0) }}</td>
          <td class="num">S/ {{ number_format($linea, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  @php
    $subTotalView = $cotizacion->total_sin_igv ?? $subtotal;
    $descGlobal   = $cotizacion->descuento_global ?? 0;
    $igvMonto     = $cotizacion->igv_monto ?? 0;
    $totalFinal   = $cotizacion->total_con_igv ?? ($subTotalView - $descGlobal + $igvMonto);
  @endphp

  <!-- ===== TOTALES + FIRMA + NOTA ===== -->
<table class="wrap-totals">
  <tr>
    <!-- 📝 Columna izquierda: Nota -->
    <td style="width:54%; vertical-align:top; padding-right:0px;">
      <div class="totals-note">
        <span class="strong">Nota:</span><br>
        El servicio inicio con un pago de 50% del total al aceptar esta cotización. El saldo restante
        se abonará al finalizar el proyecto y antes de la entrega de los archivos. Los pagos iniciales pueden ser negociados en un 30% - 40%.
      </div>
    </td>

    <!-- 💰 Columna derecha: Totales + Firma -->
    <td style="width:75%; vertical-align:top; text-align:right;">

      <table class="totals">
        <tr>
          <td>SUB TOTAL:</td>
          <td class="right">S/ {{ number_format($subTotalView, 2) }}</td>
        </tr>
        @if($descGlobal > 0)
        <tr>
          <td>DESCUENTO:</td>
          <td class="right">- S/ {{ number_format($descGlobal, 2) }}</td>
        </tr>
        @endif
        <tr>
          <td>IGV (18%):</td>
          <td class="right">
            @if($igvMonto > 0)
              S/ {{ number_format($igvMonto, 2) }}
            @else
              –
            @endif
          </td>
        </tr>
        <tr class="grand-row">
          <td colspan="2">
            <div class="grand-box">
              <div class="grand-label">TOTAL:</div>
              <div class="grand-amount">S/ {{ number_format($totalFinal, 2) }}</div>
            </div>
          </td>
        </tr>
      </table>

      <!-- Firma debajo de los totales -->
      <div class="signature-box">
        <img class="signature-img" src="{{ public_path('imagenes/firmadaniel.png') }}" alt="Firma">
        <div class="signature-label">Firma</div>
        <div class="signature-line"></div>
        <div class="signature-name">Lorenzo Daniel S.O</div>
        <div class="signature-role">G.General CEO – Agencia DN</div>
      </div>

    </td>
  </tr>
</table>



  <!-- ===== SALTO DE PÁGINA ===== -->
  <div class="page-break"></div>

  <!-- ===== FOOTER ===== -->
  <table class="footer">
    <tr>
      <td style="width: 50%;">
        <span class="pill">¿Preguntas?</span>
        <div style="margin-top: 8px;">
          Llámanos: +51 959 114 988<br>
          Escríbenos: soporte@agenciadn.pe
        </div>
      </td>
      <td style="width: 50%;">
        <span class="pill">Términos y Condiciones</span>
        <div style="margin-top: 8px;">
          Esta cotización es válida por 7 días. Plazos, entregables y propiedad intelectual
          se rigen por el acuerdo marco del servicio.
        </div>
      </td>
    </tr>
  </table>


  <!-- ===== TABLA DE TIEMPOS DE ENTREGA ===== -->
<table class="delivery-table">
  <thead>
    <tr>
      <th style="width: 60%;">Servicio</th>
      <th style="width: 40%;">Tiempo estimado de entrega</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Página web / Tienda virtual</td>
      <td class="num">7 - 14 días</td>
    </tr>
    <tr>
      <td>Diseño de logo y branding</td>
      <td class="num">4 - 7 días</td>
    </tr>
    <tr>
      <td>Publicidad digital</td>
      <td class="num">5 - 7 días</td>
    </tr>
    <tr>
      <td>Diseño de flyer / Edición de video</td>
      <td class="num">2 - 4 días</td>
    </tr>
  </tbody>
</table>

<div class="note-delivery">
  <strong>Nota:</strong> Estas fechas son referenciales. El tiempo real dependerá del tipo de proyecto y su alcance.
</div>


</body>
</html>
