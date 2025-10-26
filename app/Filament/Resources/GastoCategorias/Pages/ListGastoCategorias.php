<?php

namespace App\Filament\Resources\GastoCategorias\Pages;

use App\Filament\Resources\GastoCategorias\GastoCategoriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGastoCategorias extends ListRecords
{
    protected static string $resource = GastoCategoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
