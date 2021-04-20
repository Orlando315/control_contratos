<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Unidad;

class UnidadesSheet implements FromQuery, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    /**
     * Query con la informacion a exportar
     * 
     * @return Builder
     */
    public function query()
    {
      return Unidad::select('id', 'nombre');
    }

    /**
     * Titulo de la hoja
     * 
     * @return string
     */
    public function title(): string
    {
      return 'Unidades';
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
          'Se debe colocar solo el número ID.'
        ],
        [
          'ID',
          'Nombre',
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
        'A' => 7,
        'B' => 14,
      ];
    }
}
