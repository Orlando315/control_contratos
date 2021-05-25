<?php

namespace App\Integrations\Logger;

use Illuminate\Support\ServiceProvider;
use App\Integrations\Logger\ActivityLogger;
use App\Integrations\Logger\ActivityLogStatus;
use App\Log;

class ActivitylogServiceProvider extends ServiceProvider
{
    /**
     * Registrar las clases de la integracion
     *
     * @return void
     */
    public function register()
    {
      $this->app->bind(ActivityLogger::class);
      $this->app->singleton(ActivityLogStatus::class);
    }

    /**
     * Obtener una instancia del Modelo usado para los Logs
     *
     * @return \App\Log
     */
    public static function getActivityModelInstance()
    {
      return new Log;
    }
}
