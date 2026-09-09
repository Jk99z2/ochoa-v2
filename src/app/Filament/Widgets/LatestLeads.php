<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestLeads extends TableWidget
{
    protected static ?string $heading = "Leads recientes";
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                $user = auth()->user();

                $query = Lead::query()->latest();

                if (! ($user?->is_admin ?? false)) {
                    $query->where("agente_id", $user?->agente?->id ?? -1);
                }

                return $query;
            })
            ->columns([
                TextColumn::make("nombre")
                    ->label("Nombre")
                    ->searchable(),

                TextColumn::make("propiedad.titulo")
                    ->label("Propiedad")
                    ->limit(20)
                    ->placeholder("General"),

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
                    }),

                TextColumn::make("created_at")
                    ->label("Recibido")
                    ->since(),
            ])
            ->paginated([5]);
    }
}
