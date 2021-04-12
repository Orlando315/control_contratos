<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\InventarioV2;

class InventarioV2Export implements FromQuery, WithTitle, WithHeadings, WithStyles, WithColumnWidths, WithMapping
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
        'Nombre',
        'Tipo código',
        'Código',
        'Stock',
        'Stock mínimo',
        'Unidad',
        'Descripción',
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
        'B' => 10,
        'C' => 10,
        'D' => 10,
        'E' => 16,
        'F' => 16,
        'G' => 30,
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
        'Nombre' => $inventario->nombre,
        'Tipo código' => $inventario->tipo_codigo,
        'Código' => $inventario->codigo,
        'Stock' => $inventario->stock ?? 0,
        'Stock mínimo' => $inventario->stock_minimo,
        'Unidad' => $inventario->unidad->nombre,
        'Descripción' => $inventario->descripcion,
      ];
    }
}
