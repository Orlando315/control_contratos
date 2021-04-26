<?php

use App\Integrations\FacturacionSii;

if (! function_exists('sii')) {
    /**
     * Obtener una instancia de FacturacionSii
     *
     * @return \App\Integrations\FacturacionSii
     */
    function sii()
    {
      return new FacturacionSii;
    }
}
