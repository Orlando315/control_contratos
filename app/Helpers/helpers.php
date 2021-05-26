<?php

use App\Integrations\Sii\FacturacionSii;
use App\Integrations\ActivityLogger;
use App\Integrations\ActivityLogStatus;
use Illuminate\Support\Facades\Route;

if (! function_exists('sii')) {
    /**
     * Obtener una instancia de FacturacionSii
     *
     * @return \App\Integrations\Sii\FacturacionSii
     */
    function sii()
    {
      return app(FacturacionSii::class);
    }
}

if (! function_exists('activityLog')) {
    /**
     * Obtener instancia para crear un Log
     * 
     * @param  string|null $logName
     * @return \App\Integrations\ActivityLogger
     */
    function activityLog($logName = null): ActivityLogger
    {
      $logStatus = app(ActivityLogStatus::class);

      return app(ActivityLogger::class)
          ->useLog($logName)
          ->setLogStatus($logStatus);
    }
}

if (! function_exists('route_exists')) {
    /**
     * Evaluar si una ruta existe segun el nombre proporcionado
     * 
     * @param  string  $routeName
     * @return bool
     */
    function route_exists($routeName)
    {
      return Route::has($routeName);
    }
}
