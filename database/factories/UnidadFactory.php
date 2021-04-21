<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Unidad;
use Faker\Generator as Faker;

$factory->define(Unidad::class, function (Faker $faker) {
    return [
      'empresa_id' => 1,
      'nombre' => ucfirst($faker->word),
    ];
});
