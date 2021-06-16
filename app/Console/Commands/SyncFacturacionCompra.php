<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\FacturacionCompra;

class SyncFacturacionCompra extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facturacion:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizar informacion de Facturacion (Orden de Compra)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $facturaciones = FacturacionCompra::all();

      foreach ($facturaciones as $facturacion){
        $factura = sii()->consultaFactura($facturacion->codigo);
        $facturacion->fill($factura);
        $facturacion->save();
      }
    }
}
