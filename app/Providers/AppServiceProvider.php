<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\{Anticipo, Solicitud};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      view()->composer('*', function ($view){
        $notificationSolicitudesAnticiposPendientes = Auth::check() && Auth::user()->isAdmin() ? Anticipo::pendientes()->get() : [];
        $notificationSolicitudesPendientes = Auth::check() && Auth::user()->isAdmin() ? Solicitud::pendientes()->get() : [];

        $view->with(compact('notificationSolicitudesAnticiposPendientes', 'notificationSolicitudesPendientes'));
      });
    }
}
