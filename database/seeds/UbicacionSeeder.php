<?php

use Illuminate\Database\Seeder;
use App\Ubicacion;

class UbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(Ubicacion::class, 5)->create();
    }
}
