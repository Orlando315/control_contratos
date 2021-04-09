<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{Partida, contrato};
use Faker\Generator as Faker;

$factory->define(Partida::class, function (Faker $faker) {
    $contrato = Contrato::first();
    $tipos = Partida::getTipos();

    return [
      'empresa_id' => $contrato->empresa_id,
      'contrato_id' => $contrato->id,
      'tipo' => $tipos[array_rand($tipos)],
      'codigo' => $faker->word,
      'descripcion' => $faker->sentence,
      'monto' => (rand(1, 9) * 1000),
    ];
});
