<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\{Anticipo, Solicitud, EmpleadosEvento};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      setlocale(LC_ALL, config('app.locale'));
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
        $notificationEmpleadoEventosPendientes = Auth::check() && Auth::user()->isAdmin() ? EmpleadosEvento::pendientes()->get() : [];

        $view->with(compact('notificationSolicitudesAnticiposPendientes', 'notificationSolicitudesPendientes', 'notificationEmpleadoEventosPendientes'));
      });
    }
}
