<?php

namespace App\Filament\Resources\GastoCategorias;

use App\Filament\Resources\GastoCategorias\Pages\CreateGastoCategoria;
use App\Filament\Resources\GastoCategorias\Pages\EditGastoCategoria;
use App\Filament\Resources\GastoCategorias\Pages\ListGastoCategorias;
use App\Filament\Resources\GastoCategorias\Schemas\GastoCategoriaForm;
use App\Filament\Resources\GastoCategorias\Tables\GastoCategoriasTable;
use App\Models\GastoCategoria;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GastoCategoriaResource extends Resource
{
    protected static ?string $model = GastoCategoria::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return GastoCategoriaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GastoCategoriasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGastoCategorias::route('/'),
            'create' => CreateGastoCategoria::route('/create'),
            'edit' => EditGastoCategoria::route('/{record}/edit'),
        ];
    }
}
