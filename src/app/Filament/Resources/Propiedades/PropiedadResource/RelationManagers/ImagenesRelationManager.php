<?php

namespace App\Filament\Resources\Propiedades\PropiedadResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImagenesRelationManager extends RelationManager
{
    protected static string $relationship = "imagenes";

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make("path")
                    ->label("Imagen")
                    ->image()
                    ->directory("propiedades")
                    ->disk("public")
                    ->visibility("public")
                    ->imageEditor()
                    ->required()
                    ->columnSpanFull(),

                TextInput::make("alt")
                    ->label("Texto alternativo")
                    ->maxLength(160)
                    ->helperText("Descripción breve de la imagen (accesibilidad y SEO)"),

                Toggle::make("principal")
                    ->label("Imagen principal")
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute("alt")
            ->reorderable("orden")
            ->defaultSort("orden")
            ->columns([
                ImageColumn::make("path")
                    ->label("Vista previa")
                    ->square(),

                TextColumn::make("alt")
                    ->label("Alt")
                    ->searchable()
                    ->limit(30),

                IconColumn::make("principal")
                    ->label("Principal")
                    ->boolean(),

                TextColumn::make("orden")
                    ->label("Orden")
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
