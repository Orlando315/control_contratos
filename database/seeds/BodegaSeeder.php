<?php

use Illuminate\Database\Seeder;
use App\Bodega;

class BodegaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(Bodega::class, 5)->create();
    }
}
