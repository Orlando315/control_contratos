<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\{InventarioUpdateSheet, UnidadesSheet, CategoriasSheet, BodegasUbicacionesSheet};

class InventarioV2UpdateTemplate implements WithMultipleSheets
{
    use Exportable;

    /**
     * Hojas del Excel
     * 
     * @return array
     */
    public function sheets(): array
    {
      $sheets[0] = new InventarioUpdateSheet;
      $sheets[1] = new UnidadesSheet;
      $sheets[2] = new CategoriasSheet;
      $sheets[3] = new BodegasUbicacionesSheet;

      return $sheets;
    }
}
