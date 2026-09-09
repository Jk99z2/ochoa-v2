<?php

namespace App\Filament\Widgets;

use App\Models\Agente;
use App\Models\Lead;
use App\Models\Propiedad;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();
        $isAdmin = $user?->is_admin ?? false;
        $miAgenteId = $user?->agente?->id;

        if ($isAdmin) {
            $totalPropiedades = Propiedad::where("publicada", true)->count();
            $vendidasRentadas = Propiedad::whereIn("estado", ["vendida", "rentada"])->count();
            $leadsEsteMes = Lead::whereMonth("created_at", now()->month)
                ->whereYear("created_at", now()->year)
                ->count();
            $agentesActivos = Agente::where("activo", true)->count();

            return [
                Stat::make("Propiedades publicadas", $totalPropiedades)
                    ->icon("heroicon-o-home")
                    ->color("success"),

                Stat::make("Vendidas / Rentadas", $vendidasRentadas)
                    ->icon("heroicon-o-check-circle")
                    ->color("warning"),

                Stat::make("Leads este mes", $leadsEsteMes)
                    ->icon("heroicon-o-envelope")
                    ->color("info"),

                Stat::make("Agentes activos", $agentesActivos)
                    ->icon("heroicon-o-users")
                    ->color("gray"),
            ];
        }

        $misLeadsEsteMes = Lead::where("agente_id", $miAgenteId ?? -1)
            ->whereMonth("created_at", now()->month)
            ->whereYear("created_at", now()->year)
            ->count();

        $misLeadsTotal = Lead::where("agente_id", $miAgenteId ?? -1)->count();

        return [
            Stat::make("Mis leads este mes", $misLeadsEsteMes)
                ->icon("heroicon-o-envelope")
                ->color("info"),

            Stat::make("Mis leads totales", $misLeadsTotal)
                ->icon("heroicon-o-chart-bar")
                ->color("success"),
        ];
    }
}
