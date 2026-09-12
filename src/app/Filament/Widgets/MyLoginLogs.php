<?php

namespace App\Filament\Widgets;

use App\Models\LoginLog;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class MyLoginLogs extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected static ?string $heading = "Historial de inicios de sesión";

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => LoginLog::query()->where("user_id", auth()->id())->latest())
            ->columns([
                TextColumn::make("ip_address")
                    ->label("Dirección IP"),

                TextColumn::make("user_agent")
                    ->label("Dispositivo / Navegador")
                    ->limit(60)
                    ->tooltip(fn (?string $state): ?string => $state)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make("created_at")
                    ->label("Fecha")
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort("created_at", "desc")
            ->paginated([5, 10, 25]);
    }
}
