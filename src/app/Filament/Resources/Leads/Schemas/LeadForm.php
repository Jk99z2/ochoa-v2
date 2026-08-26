<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make("propiedad_id")
                    ->label("Propiedad")
                    ->relationship("propiedad", "titulo")
                    ->searchable()
                    ->preload()
                    ->nullable(),

                TextInput::make("nombre")
                    ->label("Nombre")
                    ->required()
                    ->maxLength(120),

                TextInput::make("email")
                    ->label("Email")
                    ->email()
                    ->maxLength(120),

                TextInput::make("telefono")
                    ->label("Teléfono")
                    ->tel()
                    ->maxLength(30),

                Textarea::make("mensaje")
                    ->label("Mensaje")
                    ->rows(4)
                    ->columnSpanFull(),

                Select::make("origen")
                    ->label("Origen")
                    ->options([
                        "formulario" => "Formulario",
                        "whatsapp" => "WhatsApp",
                        "llamada" => "Llamada",
                        "otro" => "Otro",
                    ])
                    ->default("formulario")
                    ->required(),

                Select::make("estatus")
                    ->label("Estatus")
                    ->options([
                        "nuevo" => "Nuevo",
                        "contactado" => "Contactado",
                        "en_proceso" => "En proceso",
                        "cerrado" => "Cerrado",
                        "perdido" => "Perdido",
                    ])
                    ->default("nuevo")
                    ->required(),
            ]);
    }
}
