<?php

namespace App\Filament\Pages;

use App\Models\Configuracion as ConfiguracionModel;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Configuracion extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected string $view = "filament.pages.configuracion";

    public ?array $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->is_admin ?? false;
    }

    public function getTitle(): string
    {
        return "Configuración del sitio";
    }

    public static function getNavigationLabel(): string
    {
        return "Configuración";
    }

    public function mount(): void
    {
        $this->data = ConfiguracionModel::actual()->toArray();
        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath("data")
            ->components([
                Section::make("Identidad del sitio")
                    ->columns(2)
                    ->components([
                        TextInput::make("nombre_sitio")
                            ->label("Nombre del sitio")
                            ->required()
                            ->maxLength(255),

                        FileUpload::make("logo_path")
                            ->label("Logo")
                            ->image()
                            ->disk("public")
                            ->directory("configuracion"),

                        FileUpload::make("favicon_path")
                            ->label("Favicon")
                            ->image()
                            ->disk("public")
                            ->directory("configuracion"),
                    ]),

                Section::make("Contacto - encabezado")
                    ->columns(2)
                    ->components([
                        TextInput::make("telefono_oficina")
                            ->label("Teléfono oficina"),

                        TextInput::make("telefono_celular")
                            ->label("Teléfono celular"),
                    ]),

                Section::make("Contacto - pie de página")
                    ->columns(2)
                    ->components([
                        TextInput::make("direccion")
                            ->label("Dirección")
                            ->columnSpanFull(),

                        TextInput::make("email_contacto")
                            ->label("Email de contacto")
                            ->email(),

                        TextInput::make("horario")
                            ->label("Horario de atención"),

                        TextInput::make("facebook_url")
                            ->label("URL de Facebook")
                            ->url(),

                        TextInput::make("instagram_url")
                            ->label("URL de Instagram")
                            ->url(),

                        TextInput::make("whatsapp_numero")
                            ->label("Número de WhatsApp")
                            ->helperText("Formato internacional sin espacios, ej: 523141234567"),
                    ]),

                Section::make("Landing page")
                    ->columns(1)
                    ->components([
                        TextInput::make("hero_titulo")
                            ->label("Título del hero (si no hay propiedades destacadas)"),

                        Textarea::make("hero_subtitulo")
                            ->label("Subtítulo del hero")
                            ->rows(2),

                        Textarea::make("mapa_embed_url")
                            ->label("URL de Google Maps (embed)")
                            ->rows(2)
                            ->helperText("Pega la URL completa del atributo src del iframe de Google Maps."),
                    ]),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make("save")
                ->label("Guardar cambios")
                ->submit("save"),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        ConfiguracionModel::actual()->update($data);

        Notification::make()
            ->title("Configuración guardada")
            ->success()
            ->send();
    }
}
