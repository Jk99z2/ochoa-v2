<?php

namespace App\Filament\Resources\Tipos\Pages;

use App\Filament\Resources\Tipos\TipoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTipos extends ManageRecords
{
    protected static string $resource = TipoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
