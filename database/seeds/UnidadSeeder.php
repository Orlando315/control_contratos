<?php

use Illuminate\Database\Seeder;
use App\Unidad;

class UnidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Unidad::create([
        'empresa_id' => null,
        'nombre' => 'Unidad',
        'status' => true,
      ]);

      factory(Unidad::class, 4)->create();
    }
}
