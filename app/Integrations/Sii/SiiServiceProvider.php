<?php

namespace App\Integrations\Sii;

use Illuminate\Support\ServiceProvider;
use App\Integrations\Sii\FacturacionSii;
use App\Integrations\Sii\FacturacionSiiAccount;

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
      $this->app->singleton(FacturacionSiiAccount::class);
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
