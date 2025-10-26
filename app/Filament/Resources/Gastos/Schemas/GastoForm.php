<?php

namespace App\Filament\Resources\Gastos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Actions\Action;
use App\Models\Proveedor;

class GastoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // PROVEEDOR (relación) — sin labels nulos
                Forms\Components\Select::make('proveedor_id')
                    ->label('Proveedor')
                    ->helperText('Opcional: busca por nombre, razón social o documento.')
                    ->relationship('proveedor', 'id') // usamos 'id' y abajo definimos cómo mostrar el label real
                    ->getOptionLabelFromRecordUsing(fn (Proveedor $record) => $record->etiqueta) // siempre string
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->nullable()
                    // Búsqueda con label seguro (nunca null)
                    ->getSearchResultsUsing(function (string $search) {
                        return Proveedor::query()
                            ->where(function ($q) use ($search) {
                                $q->where('nombre', 'like', "%{$search}%")
                                  ->orWhere('razon_social', 'like', "%{$search}%")
                                  ->orWhere('documento_numero', 'like', "%{$search}%");
                            })
                            ->orderByDesc('id')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function (Proveedor $p) {
                                $label = $p->etiqueta
                                    ?? ($p->nombre ?: ($p->razon_social ?: ('Proveedor #'.$p->id)));
                                return [$p->id => $label];
                            })
                            ->toArray();
                    })
                    // Crear proveedor en línea
                    ->createOptionForm([
                        Forms\Components\Select::make('tipo')
                            ->label('Tipo')
                            ->options([
                                'empresa'   => 'Empresa',
                                'empleado'  => 'Empleado',
                                'freelance' => 'Freelance',
                            ])->required()->native(false),

                        Forms\Components\TextInput::make('razon_social')
                            ->label('Razón Social')
                            ->visible(fn (callable $get) => $get('tipo') === 'empresa'),

                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->visible(fn (callable $get) => $get('tipo') !== 'empresa'),

                        Forms\Components\Select::make('documento_tipo')
                            ->label('Documento')
                            ->options(['RUC'=>'RUC','DNI'=>'DNI','CE'=>'CE'])
                            ->native(false),

                        Forms\Components\TextInput::make('documento_numero')->label('N° Documento'),
                        Forms\Components\TextInput::make('telefono')->label('Teléfono')->tel(),
                        Forms\Components\TextInput::make('email')->label('Email')->email(),

                        Forms\Components\Select::make('pago_preferido')
                            ->label('Pago preferido')
                            ->options([
                                'transferencia'=>'Transferencia',
                                'yape'=>'Yape',
                                'plin'=>'Plin',
                                'efectivo'=>'Efectivo',
                            ])->native(false)->default('efectivo'),

                        Forms\Components\TextInput::make('cuenta')
                            ->label('Cuenta / CCI / Yape / Plin'),

                        Forms\Components\Textarea::make('notas')->label('Notas')->columnSpanFull(),
                    ])
                    ->createOptionAction(fn (Action $action) => $action->label('Nuevo proveedor')),

                // CATEGORÍA
                Forms\Components\Select::make('categoria_id')
                    ->label('Categoría')
                    ->relationship('categoria', 'nombre')
                    ->native(false)
                    ->required()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre de la categoría')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100),

                        Forms\Components\Toggle::make('es_fijo')
                            ->label('¿Es un gasto fijo?')
                            ->helperText('Por ejemplo: alquiler, luz, agua, etc.')
                            ->default(false),
                    ])
                    ->createOptionAction(
                        fn (\Filament\Actions\Action $action) => $action->label('Nueva categoría')
                    ),

                // CAMPOS DEL GASTO
                Forms\Components\TextInput::make('descripcion')
                    ->label('Descripción')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('monto_total')
                    ->label('Monto (S/)')
                    ->prefix('S/')
                    ->numeric()
                    ->rule('min:0')
                    ->required(),

                Forms\Components\Select::make('estado_pago')
                    ->label('Estado de pago')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagado'    => 'Pagado',
                    ])->default('pendiente')->native(false)->required(),

                Forms\Components\Select::make('metodo_pago')
                    ->label('Método de pago')
                    ->options([
                        'efectivo'      => 'Efectivo',
                        'transferencia' => 'Transferencia',
                        'yape'          => 'Yape',
                        'plin'          => 'Plin',
                    ])->default('efectivo')->native(false)->required(),

                Forms\Components\DatePicker::make('fecha_emision')
                    ->label('Fecha de emisión')
                    ->required(),

                Forms\Components\DatePicker::make('fecha_pago')
                    ->label('Fecha de pago')
                    ->nullable(),

                Forms\Components\FileUpload::make('comprobante_path')
                    ->label('Comprobante (imagen)')
                    ->image()
                    ->directory('gastos/comprobantes')
                    ->downloadable()
                    ->openable()
                    ->nullable(),

                Forms\Components\Textarea::make('notas')
                    ->label('Notas')
                    ->columnSpanFull()
                    ->rows(3),
            ])
            ->columns(2);
    }
}
