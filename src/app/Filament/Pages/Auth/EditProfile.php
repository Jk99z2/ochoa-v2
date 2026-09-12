<?php

namespace App\Filament\Pages\Auth;

use App\Filament\Widgets\MyLoginLogs;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;

class EditProfile extends BaseEditProfile
{
    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
                ...Arr::wrap($this->getMultiFactorAuthenticationContentComponent()),
                $this->getLoginHistoryComponent(),
            ]);
    }

    protected function getLoginHistoryComponent(): Component
    {
        return Section::make("Historial de inicios de sesión")
            ->description("Los últimos inicios de sesión registrados en tu cuenta.")
            ->schema([
                Livewire::make(MyLoginLogs::class),
            ]);
    }
}
