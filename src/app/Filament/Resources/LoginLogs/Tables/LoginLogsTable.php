<?php

namespace App\Filament\Resources\LoginLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoginLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("user.name")
                    ->label("Usuario")
                    ->searchable()
                    ->sortable(),

                TextColumn::make("user.email")
                    ->label("Email")
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make("ip_address")
                    ->label("Dirección IP")
                    ->searchable(),

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
            ->filters([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }
}
