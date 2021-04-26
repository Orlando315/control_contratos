<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\InventarioV2;

class InventarioUpdateSheet implements FromQuery, WithTitle, WithHeadings, WithStyles, WithColumnWidths, WithMapping, WithStrictNullComparison
{
    use Exportable;

    /**
     * Query con la informacion a exportar
     * 
     * @return Builder
     */
    public function query()
    {
      return InventarioV2::with('unidad');
    }

    /**
     * Titulo de la hoja
     * 
     * @return string
     */
    public function title(): string
    {
      return 'Inventario';
    }

    /**
     * Cabeceras
     * 
     * @return array
     */
    public function headings(): array
    {
      return [
        [
          'Los campos con asterisco (*), son obligatorios. De no contenerlo, no se guardará la información de ese Pruducto.'
        ],
        [
          'El ID de las Unidades, Bodega, Ubicación y Categorías se puede observar en la hojas con dichos nombres. Se debe colocar solo el número ID.'
        ],
        [
          'ID',
          'Nombre *',
          'Tipo código',
          'Código',
          'Unidad ID',
          'Bodega ID',
          'Ubicación ID',
          'Stock',
          'Stock mínimo',
          'Descripción',
          'Categoría #1',
          'Categoría #2',
          'Categoría #3',
          'Categoría #4',
          'Categoría #5',
          'Categoría #6',
        ]
      ];
    }

    /**
     * Establecer estilos de las filas
     * 
     * @param  \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
      return [
        1 => ['font' => ['color' => ['argb' => 'FFFF0000']]],
        2 => ['font' => ['color' => ['argb' => 'FFFF0000']]],
        3 => ['font' => ['bold' => true, 'size' => 12]],
      ];
    }

    /**
     * Establecer el ancho de las columnas
     * 
     * @return array
     */
    public function columnWidths(): array
    {
      return [
        'A' => 5,
        'B' => 30,
        'C' => 12,
        'D' => 10,
        'E' => 13,
        'F' => 13,
        'G' => 13,
        'H' => 10,
        'I' => 16,
        'J' => 30,
        'K' => 17,
        'L' => 17,
        'M' => 17,
        'N' => 17,
        'O' => 17,
        'P' => 17,
      ];
    }

    /**
     * Mapear los datos de cada modelo a exportar como array
     * 
    * @param App\InventarioV2  $inventario
    */
    public function map($inventario): array
    {
      $dataInventario = [
        $inventario->id,
        $inventario->nombre,
        $inventario->tipo_codigo,
        $inventario->codigo,
        $inventario->unidad_id,
        $inventario->bodega_id,
        $inventario->ubicacion_id,
        $inventario->stock ?? 0,
        $inventario->stock_minimo,
        $inventario->descripcion,
      ];

      $categorias = $inventario->categorias->pluck('id')->toArray();

      return array_merge($dataInventario, $categorias);
    }
}
