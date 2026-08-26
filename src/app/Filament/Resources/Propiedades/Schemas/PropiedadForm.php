<?php

namespace App\Filament\Resources\Propiedades\Schemas;

use App\Models\Agente;
use App\Models\Tipo;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PropiedadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Información general")
                    ->columns(2)
                    ->components([
                        TextInput::make("titulo")
                            ->label("Título")
                            ->required()
                            ->maxLength(160)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) =>
                                $set("slug", \Illuminate\Support\Str::slug($state))
                            )
                            ->columnSpanFull(),

                        TextInput::make("slug")
                            ->label("Slug")
                            ->required()
                            ->maxLength(180)
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        Select::make("agente_id")
                            ->label("Agente")
                            ->relationship("agente", "nombre")
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make("tipo_id")
                            ->label("Tipo")
                            ->relationship("tipo", "nombre")
                            ->searchable()
                            ->preload()
                            ->required(),

                        Textarea::make("descripcion")
                            ->label("Descripción")
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make("Operación y precio")
                    ->columns(3)
                    ->components([
                        Select::make("operacion")
                            ->label("Operación")
                            ->options([
                                "venta" => "Venta",
                                "renta" => "Renta",
                                "venta_renta" => "Venta y renta",
                            ])
                            ->required(),

                        TextInput::make("precio")
                            ->label("Precio")
                            ->numeric()
                            ->required()
                            ->prefix("$"),

                        TextInput::make("moneda")
                            ->label("Moneda")
                            ->default("MXN")
                            ->maxLength(3)
                            ->required(),

                        TextInput::make("mantenimiento")
                            ->label("Mantenimiento")
                            ->numeric()
                            ->prefix("$"),

                        Select::make("estado")
                            ->label("Estado")
                            ->options([
                                "borrador" => "Borrador",
                                "disponible" => "Disponible",
                                "apartada" => "Apartada",
                                "vendida" => "Vendida",
                                "rentada" => "Rentada",
                            ])
                            ->default("borrador")
                            ->required(),

                        TextInput::make("vistas")
                            ->label("Vistas")
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                    ]),

                Section::make("Publicación")
                    ->columns(2)
                    ->components([
                        Toggle::make("publicada")
                            ->label("Publicada")
                            ->default(false),

                        Toggle::make("destacada")
                            ->label("Destacada")
                            ->default(false),
                    ]),

                Section::make("Características")
                    ->columns(3)
                    ->components([
                        TextInput::make("m2_terreno")
                            ->label("M² terreno")
                            ->numeric(),

                        TextInput::make("m2_construccion")
                            ->label("M² construcción")
                            ->numeric(),

                        TextInput::make("recamaras")
                            ->label("Recámaras")
                            ->numeric(),

                        TextInput::make("banios")
                            ->label("Baños")
                            ->numeric()
                            ->step(0.5),

                        TextInput::make("niveles")
                            ->label("Niveles")
                            ->numeric(),

                        TextInput::make("estacionamientos")
                            ->label("Estacionamientos")
                            ->numeric(),

                        TextInput::make("antiguedad")
                            ->label("Antigüedad (años)")
                            ->numeric(),
                    ]),

                Section::make("Amenidades")
                    ->components([
                        CheckboxList::make("amenidades")
                            ->label("Amenidades")
                            ->relationship("amenidades", "nombre")
                            ->columns(3)
                            ->searchable(),
                    ]),

                Section::make("Ubicación")
                    ->columns(2)
                    ->components([
                        TextInput::make("calle")
                            ->label("Calle")
                            ->maxLength(160),

                        TextInput::make("colonia")
                            ->label("Colonia")
                            ->maxLength(120),

                        TextInput::make("ciudad")
                            ->label("Ciudad")
                            ->default("Manzanillo")
                            ->required()
                            ->maxLength(120),

                        TextInput::make("estado_mx")
                            ->label("Estado")
                            ->default("Colima")
                            ->required()
                            ->maxLength(120),

                        TextInput::make("cp")
                            ->label("Código postal")
                            ->maxLength(5),

                        Toggle::make("ocultar_direccion")
                            ->label("Ocultar dirección exacta")
                            ->default(false),

                        TextInput::make("lat")
                            ->label("Latitud")
                            ->numeric(),

                        TextInput::make("lng")
                            ->label("Longitud")
                            ->numeric(),
                    ]),

                Section::make("Multimedia y documentos")
                    ->columns(1)
                    ->components([
                        TextInput::make("video_url")
                            ->label("URL de video")
                            ->url()
                            ->maxLength(255),

                        TextInput::make("tour_url")
                            ->label("URL de tour virtual")
                            ->url()
                            ->maxLength(255),

                        TextInput::make("expediente")
                            ->label("Expediente")
                            ->maxLength(255),
                    ]),
            ]);
    }
}
