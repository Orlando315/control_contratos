<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\{InventarioSheet, UnidadesSheet, CategoriasSheet};

class InventarioV2ImportTemplate implements WithMultipleSheets
{
    use Exportable;

    /**
     * Hojas del Excel
     * 
     * @return array
     */
    public function sheets(): array
    {
      $sheets[0] = new InventarioSheet;
      $sheets[1] = new UnidadesSheet;
      $sheets[2] = new CategoriasSheet;

      return $sheets;
    }
}
