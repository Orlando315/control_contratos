<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use App\{InventarioV2, Unidad};

class InventarioV2Import implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
      // Los datos que no cumplan con los campos requeridos, no sera tomados en cuenta
      if(!isset($row['nombre']) || !isset($row['unidad_id']) || !Unidad::find($row['unidad_id'])){
        return null;
      }

      $row['empresa_id'] = Auth::user()->empresa->id;

      return new InventarioV2($row);
    }

    /**
     * Especificarlas hojas a usar
     * 
     * @return array
     */
    public function sheets(): array
    {
      return [
        0 => $this,
      ];
    }


    /**
     * Especificar el numero de la columna que sera usada como heading
     * 
     * @return int
     */
    public function headingRow(): int
    {
      return 3;
    }
}
