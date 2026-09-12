<?php

namespace App\Providers;

use App\Listeners\LogSuccessfulLogin;
use App\Models\Configuracion;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(["welcome", "propiedades.*"], function ($view) {
            $view->with("siteConfig", Configuracion::actual());
        });

        Event::listen(Login::class, LogSuccessfulLogin::class);
    }
}
