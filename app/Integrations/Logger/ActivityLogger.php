<?php

namespace App\Integrations\Logger;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Config\Repository;
use App\User;
use App\Log;

class ActivityLogger
{
    use Macroable;

    protected ?string $defaultLogName = 'empresa';
    protected ActivityLogStatus $logStatus;
    protected $activity = null;

    public function __construct(Repository $config, ActivityLogStatus $logStatus)
    {
      $this->logStatus = $logStatus;
    }

    /**
     * Establecer el status del Log
     * 
     * @param ActivityLogStatus $logStatus
     */
    public function setLogStatus(ActivityLogStatus $logStatus)
    {
      $this->logStatus = $logStatus;

      return $this;
    }

    /**
     * Modelo al que se le realizo el cambio
     * 
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function on(Model $model)
    {
      $this->getActivity()->subject()->associate($model);

      return $this;
    }

    /**
     * User que realizo el cambio
     * 
     * @param  \App\User|int|null  $modelOrId
     * @return mixed
     */
    public function by($userOrId = null)
    {
      if($userOrId === null){
        return $this;
      }

      $user = $this->userResolver($userOrId);

      $this->getActivity()->user_id = optional($user)->id;

      return $this;
    }

    /**
     * Establecer que el Log no fue realizado por
     * alguien especifico
     */
    public function byAnonymous()
    {
      $this->activity->empresa_id = null;
      $this->activity->causer_id = null;
      $this->activity->causer_type = null;

      return $this;
    }

    /**
     * Obtener el User que realizo el Log
     * dependiendo del tipo de dato sumisnistrado
     * 
     * @param  \App\User|int|null  $modelOrId
     * @return \App\User|null [<description>]
     */
    protected function userResolver($subject = null)
    {
      if($subject instanceof Model){
        return $subject;
      }

      if(is_null($subject)){
        return Auth::user();
      }

      return User::find($subject);
    }

    /**
     * Establecer la Empresa a la que pertenece el Log
     * 
     * @param  \App\Empresa|int|null|false  $modelOrId
     */
    public function withEmpresa($modelOrId = null)
    {
      $empresa = $this->empresaResolver($modelOrId);

      $this->activity->empresa_id = optional($empresa)->id;

      return $this; 
    }

    /**
     * Establecer el Log sin una Empresa
     */
    public function withoutEmpersa()
    {
      $this->activity->empresa_id = null;

      return $this;
    }

    /**
     * Obtener la Empresa a la que pertence el Log
     * dependiendo del tipo de dato sumisnistrado
     * 
     * @param  \App\Empresa|int|null  $empresa
     * @return Model|null
     */
    protected function empresaResolver($empresa = null)
    {
      if($empresa instanceof Model){
        return $empresa;
      }

      if(is_null($empresa)){
        return Auth::check() ? Auth::user()->empresa : null;
      }

      return Empresa::find($empresa);
    }

    /**
     * Establecer el tipo de evento del Log
     * 
     * @param  string $event
     */
    public function event(string $event)
    {
      $this->activity->event = $event;

      return $this;
    }

    /**
     * Establecer propiedades para el Log
     * 
     * @param  array  $properties
     */
    public function withProperties($properties)
    {
      $this->getActivity()->properties = collect($properties);

      return $this;
    }

    /**
     * Insertar una propiedad para el Log
     * 
     * @param  string  $key
     * @param  mixed  $value
     */
    public function withProperty(string $key, $value)
    {
      $this->getActivity()->properties = $this->getActivity()->properties->put($key, $value);

      return $this;
    }

    /**
     * Establecer la fecha de creacion del Log
     * 
     * @param  DateTimeInterface $dateTime
     */
    public function createdAt(DateTimeInterface $dateTime)
    {
      $this->getActivity()->created_at = Carbon::instance($dateTime);

      return $this;
    }

    /**
     * Establecer el nombre del log a usar
     * 
     * @param  string|null  $logName
     */
    public function useLog($logName = null)
    {
      $this->getActivity()->log_name = $logName ?: $this->defaultLogName;

      return $this;
    }

    /**
     * Activar el Log
     */
    public function enableLogging()
    {
      $this->logStatus->enable();

      return $this;
    }

    /**
     * Desactivar el Log
     */
    public function disableLogging()
    {
      $this->logStatus->disable();

      return $this;
    }

    /**
     * Guardar el Log con la descripcion especificada
     * 
     * @param  string|null  $description
     * @return \App\Log
     */
    public function log($description = null)
    {
      if($this->logStatus->disabled()){
        return null;
      }

      $activity = $this->activity;

      if($activity->description || $description){
        $activity->description = $this->replacePlaceholders(
          $activity->description ?? $description,
          $activity
        );
      }

      $activity->save();

      $this->activity = null;

      return $activity;
    }

    /**
     * Reemplazar los place holders con los datos reales
     * 
     * @param  string $description
     * @param  \App\Log  $activity
     * @return string
     */
    protected function replacePlaceholders(string $description, Log $activity): string
    {
      return preg_replace_callback('/:[a-z0-9._-]+/i', function ($match) use ($activity) {
        $match = $match[0];

        $attribute = Str::before(Str::after($match, ':'), '.');

        if(! in_array($attribute, ['subject', 'user', 'properties'])){
          return $match;
        }

        $propertyName = substr($match, strpos($match, '.') + 1);

        $attributeValue = $activity->$attribute;

        if(is_null($attributeValue)){
          return $match;
        }

        $attributeValue = $attributeValue->toArray();

        return Arr::get($attributeValue, $propertyName, $match);
      }, $description);
    }

    /**
     * Obtener la instancia de Log a crear
     * 
     * @return \App\Log
     */
    protected function getActivity()
    {
      if(!$this->activity instanceof Log){
        $this->activity = ActivitylogServiceProvider::getActivityModelInstance();
        $this->useLog($this->defaultLogName)
        ->withProperties([])
        ->by($this->userResolver())
        ->withEmpresa();
      }

      return $this->activity;
    }
}
