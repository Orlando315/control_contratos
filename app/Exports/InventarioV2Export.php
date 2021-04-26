<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\InventarioV2;

class InventarioV2Export implements FromQuery, WithTitle, WithHeadings, WithStyles, WithColumnWidths, WithMapping, WithStrictNullComparison
{
    use Exportable;

    /**
     * Query con la informacion a exportar
     * 
     * @return Builder
     */
    public function query()
    {
      return InventarioV2::with([
        'unidad',
        'bodega',
        'ubicacion',
      ]);
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
        'Nombre',
        'Tipo código',
        'Código',
        'Unidad',
        'Bodega',
        'Ubicación',
        'Stock',
        'Stock mínimo',
        'Descripción',
        'Categorías',
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
        1 => ['font' => ['bold' => true, 'size' => 12]],
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
        'A' => 30,
        'B' => 12,
        'C' => 10,
        'D' => 13,
        'E' => 13,
        'F' => 13,
        'G' => 18,
        'H' => 16,
        'I' => 30,
        'J' => 17,
      ];
    }

    /**
     * Mapear los datos de cada modelo a exportar como array
     * 
    * @param App\InventarioV2  $inventario
    */
    public function map($inventario): array
    {
      return [
        $inventario->nombre,
        $inventario->tipo_codigo,
        $inventario->codigo,
        optional($inventario->unidad)->nombre,
        optional($inventario->bodega)->nombre,
        optional($inventario->ubicacion)->nombre,
        $inventario->stock ?? 0,
        $inventario->stock_minimo,
        $inventario->descripcion,
        implode(', ', $inventario->categorias->pluck('etiqueta')->toArray()),
      ];
    }
}
