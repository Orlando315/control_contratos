<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Scopes\{EmpresaScope, LatestScope};

class Log extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'log_name',
      'description',
      'subject',
      'causer',
      'properties',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'properties' => 'collection',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();
      static::addGlobalScope(new EmpresaScope);
      static::addGlobalScope(new LatestScope);
    }

    /**
     * Incluir solo los Logs con los nombres proporcionados.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $logNames
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInLog(Builder $query, ...$logNames)
    {
      if(is_array($logNames[0])){
        $logNames = $logNames[0];
      }

      return $query->whereIn('log_name', $logNames);
    }

    /**
     * Incluir solo los Logs realizado por el User proporcionado.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\User|int  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCausedBy(Builder $query, User $userOrId)
    {
      $id = $userOrId instanceof User ? $userOrId->id : $userOrId;

      return $query->where('user_id', $id);
    }

    /**
     * Incluir solo los Logs del modelo especificado.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  Model  $subject
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForSubject(Builder $query, Model $subject)
    {
      return $query
      ->where('subject_type', $subject->getMorphClass())
      ->where('subject_id', $subject->getKey());
    }

    /**
     * Incluir solo los Logs del evento especificado.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $isRequisito
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForEvent(Builder $query, string $event)
    {
      return $query->where('event', $event);
    }

    /**
     * Obtener los cambios realizados en el modelo
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getChangesAttribute()
    {
      return $this->changes();
    }

    /**
     * Obtener los cambios realizados en el modelo
     * 
     * @return array
     */
    public function getAttributesChangesAttribute()
    {
      return $this->changes()['attributes'] ?? [];
    }

    /**
     * Obtener los atributos originales antes de los cambios en el modelo
     * 
     * @return array
     */
    public function getOldChangesAttribute()
    {
      return $this->changes()['old'] ?? [];
    }

    /**
     * Obtener la url de la vista show del modelo del subject
     * 
     * @return string|null
     */
    public function getSubjectUrlAttribute()
    {
      $routeName = $this->getSubjectRouteName();
      $showRouteName = 'admin.'.$routeName.'.show';

      return route_exists($showRouteName) ? route($showRouteName, [$this->subject]) : null;
    }

    /**
     * Obtener la Empresa a la que pertenece
     */
    public function empresa()
    {
      return $this->belongsTo('App\Empresa');
    }

    /**
     * User al que pertenece el Log
     */
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    /**
     * Modelo al que se le realizo el Log
     */
    public function subject()
    {
      return $this->morphTo();
    }

    /**
     * Obtener el nombre base de la ruta del modelo del subject
     * 
     * @return string
     */
    public function getSubjectRouteName()
    {
      $routeName = $this->subject_type::$baseRouteName ?? $this->guessLogEventTitle();

      return strtolower($routeName);
    }

    /**
     * Obtener la propiedad especificada
     * 
     * @param  string|null  $propertyName
     * @return mixed
     */
    public function getProperty($propertyName)
    {
      if(is_null($propertyName)){
        return null;
      }

      $exists = array_key_exists($propertyName, $this->properties['attributes'] ?? []);
      $value = ($exists ? $this->properties['attributes'][$propertyName] : null);

      return (is_array($value) || is_object($value) ? json_encode($value) : $value);
    }

    /**
     * Obtener la propiedad especificada
     * 
     * @param  string|null  $propertyName
     * @return mixed
     */
    public function getOldProperty($propertyName)
    {
      if(is_null($propertyName)){
        return null;
      }

      $exists = array_key_exists($propertyName, $this->properties['old'] ?? []);
      $value = ($exists ? $this->properties['old'][$propertyName] : null);

      return (is_array($value) || is_object($value) ? json_encode($value) : $value);
    }

    /**
     * Obtener los cambios realizados en el modelo
     * 
     * @return \Illuminate\Support\Collection
     */
    public function changes()
    {
      if(!$this->properties instanceof Collection){
        return new Collection();
      }

      return $this->properties->only(['attributes', 'old']);
    }

    /**
     * Evaluar si el nombre del evento es el proporcionado
     * 
     * @param  string|array  $event
     * @return bool
     */
    public function isEvent($events)
    {
      $events = is_array($events) ? $events : (Str::contains($events, '|') ? explode('|', $events) : Arr::wrap($events));

      return in_array($this->event, $events);
    }

    /**
     * Evaluar si el evento es updated
     * 
     * @return bool
     */
    public function isUpdate()
    {
      return $this->isEvent('updated');
    }

    /**
     * Evaluar si el evento es deleted
     * 
     * @return bool
     */
    public function isDeleted()
    {
      return $this->isEvent('deleted');
    }

    /**
     * Obtener el nombre del modelo del subject
     * 
     * @return string
     */
    public function getLogEventTitle()
    {
      return $this->subject_type::$logEventTitle ?? $this->guessLogEventTitle();
    }

    /**
     * Obtener el nombre del modelo del subject
     * 
     * @return string
     */
    protected function guessLogEventTitle()
    {
      return class_basename($this->subject_type);
    }

    /**
     * Obtener el icono y clase dependiendo del tipo de evento
     * 
     * @return string
     */
    private function getEventIcon()
    {
      switch ($this->event) {
        case 'created':
          return ['fa-plus', 'label-success'];
          break;
        case 'updated':
          return ['fa-pencil', 'label-primary'];
          break;
        case 'deleted':
          return ['fa-times', 'label-danger'];
          break;
        case 'retrieved':
          return ['fa-search', 'label-default'];
          break;
        default:
          return ['exclamation-triangle', 'label-default'];
          break;
      }
    }

    /**
     * Traducir el nombre del evento proporcionado
     * 
     * @return string
     */
    public function getTranslatedEvent()
    {
      if($this->event == 'created'){
        return 'creado';
      }

      if($this->event == 'updated'){
        return 'modificado';
      }

      if($this->event == 'retrieved'){
        return 'consultado';
      }

      if($this->event == 'deleted'){
        return 'eliminado'; 
      }

      return $this->event;
    }

    /**
     * Obtener el icono usado para identificar el Log
     * dependiendo de su tipo
     * 
     * @return string
     */
    public function icon()
    {
      [$icon, $label] = $this->getEventIcon();
      return "<span class=\"label {$label}\"><i class=\"fa {$icon}\" aria-hidden=\"true\"></i></span>";
    }

    /**
     * Obtener el titulo del atributo proporcionado
     *
     * @param  string  $attribute
     * @return string
     */
    public function getAttributeTitle($attribute)
    {
      $subjectClass = $this->subject_type;
      return $subjectClass::$attributesTitle[$attribute] ?? $this->getAttritubeTitleFromName($attribute);
    }

    /**
     * Crear el titulo del atributo basado en el nombre del
     * atributo proporcionado
     * 
     * @param  string  $attribute
     * @return string
     */
    public function getAttritubeTitleFromName($attribute)
    {
      $attributeTitle = Str::before($attribute, '_id');

      return ucfirst(Str::replaceArray('_', [' '], $attributeTitle));
    }

    /**
     * Obtener los modelos que tienen Logs
     * 
     * @return \Illuminate\Support\Collection
     */
    public static function getLoggedModels()
    {
      $models = self::select('subject_type')
      ->distinct()
      ->get()
      ->map(function ($model) {
        return [
          'model' => $model->subject_type,
          'title' => $model->getLogEventTitle(),
        ];
      })
      ->sortBy('title');

      return $models;
    }
}
