<?php

namespace App\Filament\Resources\Agentes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AgentesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make("foto")
                    ->label("Foto")
                    ->circular(),

                TextColumn::make("nombre")
                    ->label("Nombre")
                    ->searchable()
                    ->sortable(),

                TextColumn::make("email")
                    ->label("Email")
                    ->searchable()
                    ->sortable(),

                TextColumn::make("telefono")
                    ->label("Teléfono")
                    ->searchable(),

                IconColumn::make("activo")
                    ->label("Activo")
                    ->boolean(),

                TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make("updated_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
