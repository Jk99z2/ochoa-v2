<?php

namespace App\Filament\Resources\LoginLogs;

use App\Filament\Resources\LoginLogs\Pages\ListLoginLogs;
use App\Filament\Resources\LoginLogs\Tables\LoginLogsTable;
use App\Models\LoginLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LoginLogResource extends Resource
{
    protected static ?string $model = LoginLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    public static function getModelLabel(): string
    {
        return "Inicio de sesión";
    }

    public static function getPluralModelLabel(): string
    {
        return "Inicios de sesión";
    }

    public static function getNavigationLabel(): string
    {
        return "Inicios de sesión";
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        if ($user && ! $user->is_admin) {
            return $query->where("user_id", $user->id);
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        return LoginLogsTable::configure($table);
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
            "index" => ListLoginLogs::route("/"),
        ];
    }
}
