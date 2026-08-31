<?php

namespace App\Filament\Resources\Tipos;

use App\Filament\Resources\Tipos\Pages\ManageTipos;
use App\Models\Tipo;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TipoResource extends Resource
{
    protected static ?string $model = Tipo::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $recordTitleAttribute = "nombre";

    public static function getPluralModelLabel(): string
    {
        return "Tipos";
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make("nombre")
                    ->label("Nombre")
                    ->required()
                    ->maxLength(60)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set("slug", \Illuminate\Support\Str::slug($state))
                    ),

                TextInput::make("slug")
                    ->label("Slug")
                    ->required()
                    ->maxLength(60)
                    ->unique(ignoreRecord: true),

                TextInput::make("orden")
                    ->label("Orden")
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute("nombre")
            ->defaultSort("orden")
            ->columns([
                TextColumn::make("nombre")
                    ->label("Nombre")
                    ->searchable()
                    ->sortable(),

                TextColumn::make("slug")
                    ->label("Slug")
                    ->searchable(),

                TextColumn::make("orden")
                    ->label("Orden")
                    ->sortable(),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            "index" => ManageTipos::route("/"),
        ];
    }
}
