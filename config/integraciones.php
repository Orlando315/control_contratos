<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Integración con Facturación Sii
    |--------------------------------------------------------------------------
    |
    | Aqui se especifican las variables para conectarse a la API, y definir
    | si se usa la direccion de prueba (sandbox), o produccion.
    |
    */

    'sii' => [
      'sandbox' => env('SII_SANDBOX', true),
      'url' => env('SII_URL'),
      'sandbox_url' => env('SII_SANDBOX_URL'),
    ],

];