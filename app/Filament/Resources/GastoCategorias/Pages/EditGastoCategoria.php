<?php

namespace App\Filament\Resources\GastoCategorias\Pages;

use App\Filament\Resources\GastoCategorias\GastoCategoriaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGastoCategoria extends EditRecord
{
    protected static string $resource = GastoCategoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
