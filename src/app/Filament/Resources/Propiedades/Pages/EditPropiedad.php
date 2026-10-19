<?php

namespace App\Filament\Resources\Propiedades\Pages;

use App\Filament\Resources\Propiedades\PropiedadResource;
use App\Models\Propiedad;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;

class EditPropiedad extends EditRecord
{
    protected static string $resource = PropiedadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($zip = Arr::pull($data, 'tour_zip')) {
            $data['tour_url'] = Propiedad::instalarTour($zip);
        }

        return $data;
    }

    // Refill so the consumed (already deleted) zip doesn't linger in the upload field.
    protected function afterSave(): void
    {
        $this->fillForm();
    }
}
