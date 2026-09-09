<?php

namespace App\Filament\Widgets;

use App\Models\Propiedad;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopPropiedades extends TableWidget
{
    protected static ?string $heading = "Propiedades mas vistas";

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Propiedad::query()
                ->where("publicada", true)
                ->orderByDesc("vistas"))
            ->columns([
                TextColumn::make("titulo")
                    ->label("Propiedad")
                    ->limit(30),

                TextColumn::make("agente.nombre")
                    ->label("Agente")
                    ->placeholder("Sin asignar"),

                TextColumn::make("vistas")
                    ->label("Vistas")
                    ->badge()
                    ->color("success")
                    ->sortable(),
            ])
            ->paginated([5]);
    }
}
