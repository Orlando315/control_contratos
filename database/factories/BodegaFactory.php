<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\{Bodega, User};

$factory->define(Bodega::class, function (Faker $faker) {
    return [
      'empresa_id' => 1,
      'nombre' => $faker->word,
    ];
});
