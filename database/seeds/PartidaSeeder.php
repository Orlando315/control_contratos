<?php

use Illuminate\Database\Seeder;
use App\Partida;

class PartidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(Partida::class, 5)->create();
    }
}
