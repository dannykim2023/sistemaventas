<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta DNI y RUC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{font-family:system-ui,Segoe UI,Roboto;background:#f6f7fb;margin:0;color:#111}
        .wrap{max-width:920px;margin:36px auto;padding:0 16px}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        .card{background:#fff;border-radius:12px;padding:18px;box-shadow:0 6px 20px rgba(0,0,0,.05)}
        h2{margin:0 0 10px;font-size:20px}
        form{display:flex;gap:8px;margin:10px 0}
        input{flex:1;padding:12px;border:1px solid #e5e7eb;border-radius:10px;font-size:15px}
        button{padding:12px 16px;border:0;border-radius:10px;background:#111827;color:#fff;font-weight:600;cursor:pointer}
        .alert-ok{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;padding:10px;border-radius:10px}
        .alert-err{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;padding:10px;border-radius:10px}
        .item{background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:10px 12px}
        .label{font-size:12px;color:#6b7280}
        .value{font-weight:600}
        pre{background:#0b1020;color:#e5e7eb;border-radius:10px;padding:12px;overflow:auto}
        .full{grid-column:1/-1}
    </style>
</head>
<body>
<div class="wrap">
    <h1>Consulta de documentos (DNI / RUC)</h1>
    <div class="grid">
        {{-- DNI --}}
        <div class="card">
            <h2>DNI</h2>
            <form action="{{ route('consulta.doc.dni') }}" method="POST">
                @csrf
                <input type="text" name="dni" placeholder="Ej. 12345678" maxlength="8" value="{{ $dni ?? '' }}">
                <button type="submit">Consultar DNI</button>
            </form>
            @error('dni')<div class="alert-err">{{ $message }}</div>@enderror
            @isset($dniError)<div class="alert-err">{{ $dniError }}</div>@endisset
            @isset($dniResultado)
                @if(($dniResultado['success'] ?? false) && isset($dniResultado['data']))
                    <div class="alert-ok">Consulta exitosa</div>
                    @php $d = $dniResultado['data']; @endphp
                    <div class="grid" style="margin-top:10px">
                        <div class="item"><div class="label">DNI</div><div class="value">{{ $d['numero'] ?? '' }}</div></div>
                        <div class="item"><div class="label">Verif.</div><div class="value">{{ $d['codigo_verificacion'] ?? '' }}</div></div>
                        <div class="item"><div class="label">Paterno</div><div class="value">{{ $d['apellido_paterno'] ?? '' }}</div></div>
                        <div class="item"><div class="label">Materno</div><div class="value">{{ $d['apellido_materno'] ?? '' }}</div></div>
                        <div class="item full"><div class="label">Nombres</div><div class="value">{{ $d['nombres'] ?? '' }}</div></div>
                        <div class="item full"><div class="label">Nombre completo</div><div class="value">{{ $d['nombre_completo'] ?? '' }}</div></div>
                    </div>
                @else
                    <div class="alert-err">No se encontraron datos.</div>
                @endif
            @endisset
        </div>

        {{-- RUC --}}
        <div class="card">
            <h2>RUC</h2>
            <form action="{{ route('consulta.doc.ruc') }}" method="POST">
                @csrf
                <input type="text" name="ruc" placeholder="Ej. 20123456789" maxlength="11" value="{{ $ruc ?? '' }}">
                <button type="submit">Consultar RUC</button>
            </form>
            @error('ruc')<div class="alert-err">{{ $message }}</div>@enderror
            @isset($rucError)<div class="alert-err">{{ $rucError }}</div>@endisset
            @isset($rucResultado)
                @if(($rucResultado['success'] ?? false) && isset($rucResultado['data']))
                    <div class="alert-ok">Consulta exitosa</div>
                    @php $r = $rucResultado['data']; @endphp
                    <div class="grid" style="margin-top:10px">
                        <div class="item"><div class="label">RUC</div><div class="value">{{ $r['ruc'] ?? '' }}</div></div>
                        <div class="item full"><div class="label">Razón Social</div><div class="value">{{ $r['nombre_o_razon_social'] ?? '' }}</div></div>
                        <div class="item"><div class="label">Estado</div><div class="value">{{ $r['estado'] ?? '' }}</div></div>
                        <div class="item"><div class="label">Condición</div><div class="value">{{ $r['condicion'] ?? '' }}</div></div>
                        <div class="item full"><div class="label">Dirección</div><div class="value">{{ $r['direccion_completa'] ?? ($r['direccion'] ?? '') }}</div></div>
                    </div>
                @else
                    <div class="alert-err">No se encontraron datos.</div>
                @endif
            @endisset
        </div>
    </div>
</div>
</body>
</html>
