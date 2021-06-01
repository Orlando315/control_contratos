<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\Auth;
use App\{InventarioV2, Unidad, Etiqueta, Bodega, Ubicacion};

class InventarioV2Import implements OnEachRow, WithHeadingRow, WithMultipleSheets
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
        'bodega_id',
        'ubicacion_id',
        'stock',
        'stock_minimo',
        'descripcion',
      ])
      ->toArray();
      $data['stock'] = $data['stock'] ?? 0;

      $categoriaIds = $collection->only([
        'categoria_1',
        'categoria_2',
        'categoria_3',
        'categoria_4',
        'categoria_5',
        'categoria_6',
      ]);
      $categorias = Etiqueta::find($categoriaIds);
      $unidadPredeterminada = Unidad::predeterminada();
      $unidad = Unidad::find($data['unidad_id'] ?? null);

      // Las filas que no cumplan con los campos requeridos, no seran tomadas en cuenta
      if(
        // Si no tiene nombre
        !isset($data['nombre']) ||
        (
          // Si no tiene una Unidad, y no hay una unidad global predeterminada
          is_null($unidad) &&
          is_null($unidadPredeterminada)
        )
      ){
        return null;
      }

      $bodega = Bodega::find($data['bodega_id']);
      $ubicacion = Ubicacion::find($data['ubicacion_id']);
      $data['unidad_id'] = $unidad ? $unidad->id : $unidadPredeterminada->id;
      $data['empresa_id'] = Auth::user()->empresa->id;
      $inventario = InventarioV2::create($data);
      $inventario->categorias()->attach($categorias);
      $inventario->bodegas()->attach($bodega ? [$bodega->id] : []);
      if($bodega && $ubicacion){
        $inventario->ubicaciones()->attach([$ubicacion->id]);
      }
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
