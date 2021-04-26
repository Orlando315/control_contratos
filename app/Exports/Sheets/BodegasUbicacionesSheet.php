<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Ubicacion;

class BodegasUbicacionesSheet implements FromQuery, WithTitle, WithHeadings, WithStyles, WithColumnWidths, WithMapping
{
    /**
     * Query con la informacion a exportar
     * 
     * @return Builder
     */
    public function query()
    {
      return Ubicacion::select('bodega_id', 'id', 'nombre')->with('bodega:id,nombre');
    }

   /**
    * Mapear los datos con las columnas del excel
    * 
    * @param  \App\Ubicacion  $ubicacion
    * @return array
    */
    public function map($ubicacion): array
    {
      return [
        $ubicacion->bodega_id.' | '.$ubicacion->bodega->nombre,
        $ubicacion->id.' | '.$ubicacion->nombre,
      ];
    }

    /**
     * Titulo de la hoja
     * 
     * @return string
     */
    public function title(): string
    {
      return 'Bodegas y Ubicaciones';
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
          ' '
        ],
        [
          'Se debe colocar solo el número ID de la Bodega y Ubicación.'
        ],
        [
          'ID | Bodega',
          'ID | Ubicación',
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
        'A' => 17,
        'B' => 17,
        'C' => 14,
      ];
    }
}
