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
      'url' => env('SII_URL'),
      'email' => env('SII_EMAIL'),
      'password' => env('SII_PASSWORD'),
      'provider' => env('SII_PROVIDER'),
    ],
];
