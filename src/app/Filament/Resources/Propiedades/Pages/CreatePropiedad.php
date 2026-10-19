<?php

namespace App\Filament\Resources\Propiedades\Pages;

use App\Filament\Resources\Propiedades\PropiedadResource;
use App\Models\Propiedad;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;

class CreatePropiedad extends CreateRecord
{
    protected static string $resource = PropiedadResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($zip = Arr::pull($data, 'tour_zip')) {
            $data['tour_url'] = Propiedad::instalarTour($zip);
        }

        return $data;
    }
}
