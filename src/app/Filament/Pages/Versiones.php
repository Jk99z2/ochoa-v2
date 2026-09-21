<?php

namespace App\Filament\Pages;

use App\Support\Changelog;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Versiones extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?int $navigationSort = 99;

    protected string $view = "filament.pages.versiones";

    public function getTitle(): string
    {
        return "Historial de versiones";
    }

    public static function getNavigationLabel(): string
    {
        return "Versiones";
    }

    protected function getViewData(): array
    {
        return [
            "releases" => Changelog::releases(),
            "current" => Changelog::current(),
        ];
    }
}
