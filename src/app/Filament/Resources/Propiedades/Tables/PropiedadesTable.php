<?php

namespace App\Filament\Resources\Propiedades\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PropiedadesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("titulo")
                    ->label("Título")
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make("agente.nombre")
                    ->label("Agente")
                    ->searchable()
                    ->sortable(),

                TextColumn::make("tipo.nombre")
                    ->label("Tipo")
                    ->searchable()
                    ->sortable(),

                TextColumn::make("operacion")
                    ->label("Operación")
                    ->badge()
                    ->sortable(),

                TextColumn::make("precio")
                    ->label("Precio")
                    ->money("MXN")
                    ->sortable(),

                TextColumn::make("estado")
                    ->label("Estado")
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        "disponible" => "success",
                        "borrador" => "gray",
                        "apartada" => "warning",
                        "vendida", "rentada" => "danger",
                        default => "gray",
                    })
                    ->sortable(),

                IconColumn::make("publicada")
                    ->label("Publicada")
                    ->boolean(),

                IconColumn::make("destacada")
                    ->label("Destacada")
                    ->boolean(),

                TextColumn::make("ciudad")
                    ->label("Ciudad")
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make("created_at")
                    ->label("Creado")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make("updated_at")
                    ->label("Actualizado")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make("estado")
                    ->label("Estado")
                    ->options([
                        "borrador" => "Borrador",
                        "disponible" => "Disponible",
                        "apartada" => "Apartada",
                        "vendida" => "Vendida",
                        "rentada" => "Rentada",
                    ]),

                SelectFilter::make("operacion")
                    ->label("Operación")
                    ->options([
                        "venta" => "Venta",
                        "renta" => "Renta",
                        "venta_renta" => "Venta y renta",
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
