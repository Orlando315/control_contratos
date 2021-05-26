<?php

namespace App\Integrations\Sii;

use Illuminate\Support\ServiceProvider;
use App\Integrations\Sii\FacturacionSii;

class SiiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->bind(FacturacionSii::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
