<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\{Auth, Blade};
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

      // reemplazar valores null con N/A
      Blade::directive('nullablestring', function ($expression) {
        return "<?php echo ($expression ? e($expression) : '<span class=\"text-muted\">N/A</span>'); ?>";
      });

      // Evaluar si el user tiene roles inactivos
      Blade::directive('inactiveRole', function ($expression) {
        return "<?php if (app('laratrust')->user()->hasInactiveRole({$expression})) : ?>";
      });

      // Cerrar condicion
      Blade::directive('endinactiverole', function () {
          return "<?php endif; // app('laratrust')->user()->hasInactiveRole ?>";
      });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      view()->composer('*', function ($view){
        if(Auth::check()){
          $notificationSolicitudesAnticiposPendientes = Auth::user()->isAdmin() ? Anticipo::pendientes()->get() : [];
          $notificationSolicitudesPendientes = Auth::user()->isAdmin() ? Solicitud::pendientes()->get() : [];
          $notificationEmpleadoEventosPendientes = Auth::user()->isAdmin() ? EmpleadosEvento::pendientes()->get() : [];
          $notificationRequerimientosMaterialesPendientes = Auth::user()
          ->requerimientosMaterialesFirmante()
          ->pendiente()
          ->with(['requerimiento' => function ($query){
            $query->with('userSolicitante')->withCount('productos');
          }])
          ->get();
        }else{
          $notificationSolicitudesAnticiposPendientes = [];
          $notificationSolicitudesPendientes = [];
          $notificationEmpleadoEventosPendientes = [];
          $notificationRequerimientosMaterialesPendientes = [];
        }

        $view->with(
          compact(
            'notificationSolicitudesAnticiposPendientes',
            'notificationSolicitudesPendientes',
            'notificationEmpleadoEventosPendientes',
            'notificationRequerimientosMaterialesPendientes'
          )
        );
      });
    }
}
