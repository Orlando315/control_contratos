<?php

use App\Integrations\Sii\FacturacionSii;
use App\Integrations\Sii\FacturacionSiiAccount;
use App\Integrations\Logger\ActivityLogger;
use App\Integrations\Logger\ActivityLogStatus;
use Illuminate\Support\Facades\Route;

if (! function_exists('sii')) {
    /**
     * Obtener una instancia de FacturacionSii
     *
     * @return \App\Integrations\Sii\FacturacionSii
     */
    function sii($empresa = null, $setToken = true)
    {
      $empresa = (is_null($empresa) && Auth::user()->empresa->configuracion->hasSiiAccount()) ? Auth::user()->empresa : null;
      $account = app(FacturacionSiiAccount::class)->setEmpresaAccount($empresa);

      $facturacionSii = app(FacturacionSii::class)
      ->useAccount($account);

      if($setToken){
        $facturacionSii->setToken();
      }

      return $facturacionSii;
    }
}

if (! function_exists('activityLog')) {
    /**
     * Obtener instancia para crear un Log
     * 
     * @param  string|null $logName
     * @return \App\Integrations\Logger\ActivityLogger
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
