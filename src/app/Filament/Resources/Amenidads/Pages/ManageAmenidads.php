<?php

namespace App\Filament\Resources\Amenidads\Pages;

use App\Filament\Resources\Amenidads\AmenidadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAmenidads extends ManageRecords
{
    protected static string $resource = AmenidadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
