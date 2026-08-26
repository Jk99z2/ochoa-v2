<?php

namespace App\Filament\Resources\Amenidads;

use App\Filament\Resources\Amenidads\Pages\ManageAmenidads;
use App\Models\Amenidad;
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

class AmenidadResource extends Resource
{
    protected static ?string $model = Amenidad::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $recordTitleAttribute = "nombre";
    protected static ?string $slug = "amenidades";
    public static function getModelLabel(): string
    {
        return "Amenidad";
    }

    public static function getPluralModelLabel(): string
    {
        return "Amenidades";
    }

    public static function getNavigationLabel(): string
    {
        return "Amenidades";
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make("nombre")
                    ->label("Nombre")
                    ->required()
                    ->maxLength(80)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set("slug", \Illuminate\Support\Str::slug($state))
                    ),

                TextInput::make("slug")
                    ->label("Slug")
                    ->required()
                    ->maxLength(80)
                    ->unique(ignoreRecord: true),

                TextInput::make("icono")
                    ->label("Icono")
                    ->maxLength(60)
                    ->helperText("Nombre del icono, ej: heroicon-o-home"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute("nombre")
            ->columns([
                TextColumn::make("nombre")
                    ->label("Nombre")
                    ->searchable()
                    ->sortable(),

                TextColumn::make("slug")
                    ->label("Slug")
                    ->searchable(),

                TextColumn::make("icono")
                    ->label("Icono")
                    ->searchable(),
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
            "index" => ManageAmenidads::route("/"),
        ];
    }
}
