<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\Auth;
use App\{InventarioV2, Unidad, Etiqueta};

class InventarioV2Import implements OnEachRow, WithHeadingRow
{
    /**
    * @param \Maatwebsite\Excel\Row  $row
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onRow(Row $row)
    {
      $collection = $row->toCollection();
      $data = $collection->only([
        'nombre',
        'tipo_codigo',
        'codigo',
        'unidad_id',
        'stock_minimo',
        'descripcion',
      ]);
      $categoriaIds = $collection->only([
        'categoria_1',
        'categoria_2',
        'categoria_3',
        'categoria_4',
        'categoria_5',
        'categoria_6',
      ]);
      $categorias = Etiqueta::find($categoriaIds);

      // Las filas que no cumplan con los campos requeridos, no seran tomadas en cuenta
      if(!isset($data['nombre']) || !isset($data['unidad_id']) || !Unidad::find($data['unidad_id'])){
        return null;
      }

      $data['empresa_id'] = Auth::user()->empresa->id;
      $inventario = InventarioV2::create($data->toArray());
      $inventario->categorias()->attach($categorias);
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
