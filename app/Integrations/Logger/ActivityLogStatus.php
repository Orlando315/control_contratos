<?php

namespace App\Integrations\Logger;

class ActivityLogStatus
{
    /**
     * Estado por defecto del uso de Logs
     * 
     * @var bool
     */
    protected $enabled = true;

    /**
     * Habilitar el uso de Logs
     * 
     * @return bool
     */
    public function enable(): bool
    {
      return $this->enabled = true;
    }

    /**
     * Deshabilitar el uso de Logs
     * 
     * @return bool
     */
    public function disable(): bool
    {
      return $this->enabled = false;
    }

    /**
     * Evaluar si el los Logs estan activos o no
     * 
     * @return bool
     */
    public function disabled(): bool
    {
      return $this->enabled === false;
    }
}
