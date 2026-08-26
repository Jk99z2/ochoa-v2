<?php

namespace App\Filament\Resources\Agentes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AgenteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make("nombre")
                    ->label("Nombre")
                    ->required()
                    ->maxLength(120),

                TextInput::make("email")
                    ->label("Email")
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(120),

                TextInput::make("telefono")
                    ->label("Teléfono")
                    ->tel()
                    ->maxLength(30),

                TextInput::make("whatsapp")
                    ->label("WhatsApp")
                    ->tel()
                    ->maxLength(30),

                FileUpload::make("foto")
                    ->label("Foto")
                    ->image()
                    ->directory("agentes")
                    ->disk("public")
                    ->visibility("public"),

                Toggle::make("activo")
                    ->label("Activo")
                    ->default(true),

                Select::make("user_id")
                    ->label("Usuario vinculado")
                    ->relationship("user", "name")
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }
}
