<?php

namespace App\Filament\Resources\Leads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("nombre")
                    ->label("Nombre")
                    ->searchable()
                    ->sortable(),

                TextColumn::make("propiedad.titulo")
                    ->label("Propiedad")
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make("email")
                    ->label("Email")
                    ->searchable(),

                TextColumn::make("telefono")
                    ->label("Teléfono")
                    ->searchable(),

                TextColumn::make("origen")
                    ->label("Origen")
                    ->badge()
                    ->sortable(),

                TextColumn::make("estatus")
                    ->label("Estatus")
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        "nuevo" => "info",
                        "contactado" => "warning",
                        "en_proceso" => "warning",
                        "cerrado" => "success",
                        "perdido" => "danger",
                        default => "gray",
                    })
                    ->sortable(),

                TextColumn::make("created_at")
                    ->label("Recibido")
                    ->dateTime()
                    ->sortable(),

                TextColumn::make("updated_at")
                    ->label("Actualizado")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort("created_at", "desc")
            ->filters([
                SelectFilter::make("estatus")
                    ->label("Estatus")
                    ->options([
                        "nuevo" => "Nuevo",
                        "contactado" => "Contactado",
                        "en_proceso" => "En proceso",
                        "cerrado" => "Cerrado",
                        "perdido" => "Perdido",
                    ]),

                SelectFilter::make("origen")
                    ->label("Origen")
                    ->options([
                        "formulario" => "Formulario",
                        "whatsapp" => "WhatsApp",
                        "llamada" => "Llamada",
                        "otro" => "Otro",
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
