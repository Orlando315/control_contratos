<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{Ubicacion, Bodega};
use Faker\Generator as Faker;

$factory->define(Ubicacion::class, function (Faker $faker) {
    $bodega = Bodega::first();

    return [
      'empresa_id' => 1,
      'bodega_id' => $bodega->id,
      'nombre' => ucfirst($faker->word),
    ];
});
