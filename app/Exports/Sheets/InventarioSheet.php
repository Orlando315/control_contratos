<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Unidad;

class InventarioSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    /**
     * Collection con la informacion a exportar
     * 
     * @return Builder
     */
    public function collection()
    {
      return collect();
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
          'El ID de las Unidades o Categorías se puede observar en la hojas llamadas "Unidades" Y "Categorías" respectivamente. Se debe colocar solo el número ID.'
        ],
        [
          'Nombre *',
          'Tipo código',
          'Código',
          'Unidad ID *',
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
        'A' => 30,
        'B' => 10,
        'C' => 10,
        'D' => 13,
        'E' => 16,
        'F' => 30,
        'G' => 15,
        'H' => 15,
        'I' => 15,
        'J' => 15,
        'K' => 15,
        'L' => 15,

      ];
    }
}
