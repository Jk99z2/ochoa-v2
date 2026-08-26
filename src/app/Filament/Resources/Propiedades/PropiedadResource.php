<?php

namespace App\Filament\Resources\Propiedades;

use App\Filament\Resources\Propiedades\Pages\CreatePropiedad;
use App\Filament\Resources\Propiedades\Pages\EditPropiedad;
use App\Filament\Resources\Propiedades\Pages\ListPropiedades;
use App\Filament\Resources\Propiedades\Pages\ViewPropiedad;
use App\Filament\Resources\Propiedades\Schemas\PropiedadForm;
use App\Filament\Resources\Propiedades\Schemas\PropiedadInfolist;
use App\Filament\Resources\Propiedades\Tables\PropiedadesTable;
use App\Models\Propiedad;
use App\Filament\Resources\Propiedades\PropiedadResource\RelationManagers\ImagenesRelationManager;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropiedadResource extends Resource
{
    protected static ?string $model = Propiedad::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'titulo';
    protected static ?string $slug = "propiedades";

    public static function getModelLabel(): string
    {
        return 'Propiedad';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Propiedades';
    }

    public static function getNavigationLabel(): string
    {
        return 'Propiedades';
    }

    public static function form(Schema $schema): Schema
    {
        return PropiedadForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PropiedadInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PropiedadesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ImagenesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPropiedades::route('/'),
            'create' => CreatePropiedad::route('/create'),
            'view' => ViewPropiedad::route('/{record}'),
            'edit' => EditPropiedad::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
