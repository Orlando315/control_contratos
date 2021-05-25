<?php
namespace App\Traits;

use DateInterval;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Integrations\Logger\ActivityLogger;
use App\Integrations\Logger\LogOptions;
use App\Integrations\Logger\ActivityLogStatus;

trait LogEvents
{
    protected array $oldAttributes = [];

    public LogOptions $activitylogOptions;

    public bool $enableLoggingModelsEvents = true;

    abstract public function getActivitylogOptions(): LogOptions;

    protected static function bootLogEvents()
    {
      // Hook into eloquent events that only specified in $eventToBeRecorded array,
      // checking for "updated" event hook explicitly to temporary hold original
      // attributes on the model as we'll need them later to compare against.
      static::eventsToBeRecorded()->each(function ($eventName) {
        if($eventName === 'updated'){
          static::updating(function (Model $model){
            $oldValues = (new static())->setRawAttributes($model->getRawOriginal());
            $model->oldAttributes = static::logChanges($oldValues);
          });
        }

        static::$eventName(function (Model $model) use ($eventName){
          $model->activitylogOptions = $model->getActivitylogOptions();

          if(!$model->shouldLogEvent($eventName)){
            return;
          }

          $changes = $model->attributeValuesToBeLogged($eventName);
          $description = $model->getDescriptionForEvent($eventName);
          $logName = $model->getLogNameToUse();

          if($model->isLogEmpty($changes) && !$model->activitylogOptions->submitEmptyLogs){
            return;
          }

          // Actual logging
          $logger = app(ActivityLogger::class)
            ->useLog($logName)
            ->event($eventName)
            ->on($model)
            ->withProperties($changes);

          $logger->log($description);
        });
      });
    }

    /**
     * Obtener los Logs
     */
    public function logs(): MorphMany
    {
      return $this->morphMany('App\Log', 'subject');
    }

    /**
     * Evaluar si no hay atributos cambiados para el Log
     * 
     * @param  array  $changes
     * @return bool
     */
    public function isLogEmpty(array $changes): bool
    {
      return empty($changes['attributes'] ?? []) && empty($changes['old'] ?? []);
    }

    /**
     * Deshabilitar los Logs para el modelo
     */
    public function disableLogging()
    {
      $this->enableLoggingModelsEvents = false;

      return $this;
    }

    /**
     * Habilitar los Logs para el modelo
     */
    public function enableLogging()
    {
      $this->enableLoggingModelsEvents = true;

      return $this;
    }

    /**
     * Obtener la description para el evento
     * 
     * @param  string  $eventName
     * @return string|null
     */
    public function getDescriptionForEvent(string $eventName)
    {
      if(!empty($this->activitylogOptions->descriptionForEvent)){
        return ($this->activitylogOptions->descriptionForEvent)($eventName);
      }

      return null;
    }

    /**
     * Obtener el nombre del Log a usar
     * 
     * @return string
     */
    public function getLogNameToUse()
    {
      return $this->activitylogOptions->logName;
    }

    /**
     * Get the event names that should be recorded.
     *
     * @return  Illuminate\Support\Collection
     **/
    protected static function eventsToBeRecorded(): Collection
    {
      if(isset(static::$recordEvents)){
        return collect(static::$recordEvents);
      }

      $events = collect([
        'created',
        'updated',
        'deleted',
      ]);

      if(collect(class_uses_recursive(static::class))->contains(SoftDeletes::class)){
        $events->push('restored');
      }

      return $events;
    }

    /**
     * Evaluar si el evento debe ser guardado en Log
     * 
     * @param  string  $eventName
     * @return bool
     */
    protected function shouldLogEvent(string $eventName)
    {
      $logStatus = app(ActivityLogStatus::class);

      if(!$this->enableLoggingModelsEvents || $logStatus->disabled()){
        return false;
      }

      if(!in_array($eventName, ['created', 'updated'])){
        return true;
      }

      if(Arr::has($this->getDirty(), 'deleted_at')){
        if($this->getDirty()['deleted_at'] === null){
          return false;
        }
      }

      // Do not log update event if only ignored attributes are changed.
      return (bool) count(Arr::except($this->getDirty(), $this->activitylogOptions->dontLogIfAttributesChangedOnly));
    }

    /**
     * Determines what attributes needs to be logged based on the configuration.
     *
     * @return array
     **/
    public function attributesToBeLogged()
    {
      $this->activitylogOptions = $this->getActivitylogOptions();

      $attributes = [];

      // Check if fillable attributes will be logged then merge it to the local attributes array.
      if($this->activitylogOptions->logFillable && empty($this->activitylogOptions->logAttributes)){
        $attributes = array_merge($attributes, $this->getFillable());
      }

      // Determine if unguarded attributes will be logged.
      if($this->shouldLogUnguarded()){
        // Get only attribute names, not intrested in the values here then guarded
        // attributes. get only keys than not present in guarded array, because
        // we are logging the unguarded attributes and we cant have both!

        $attributes = array_merge($attributes, array_diff(array_keys($this->getAttributes()), $this->getGuarded()));
      }

      if(!empty($this->activitylogOptions->logAttributes)){
        // Filter * from the logAttributes because will deal with it separately
        $attributes = array_merge($attributes, array_diff($this->activitylogOptions->logAttributes, ['*']));

        // If there's * get all attributes then merge it, dont respect $guarded or $fillable.
        if(in_array('*', $this->activitylogOptions->logAttributes)){
          $attributes = array_merge($attributes, array_keys($this->getAttributes()));
        }
      }

      if($this->activitylogOptions->logExceptAttributes){
        // Filter out the attributes defined in ignoredAttributes out of the local array
        $attributes = array_diff($attributes, $this->activitylogOptions->logExceptAttributes);
      }

      // Log aditional model attributes
      if($this->activitylogOptions->logAditionalAttributes){
        $attributes = array_merge($attributes, $this->activitylogOptions->logAditionalAttributes);
      }

      return $attributes;
    }

    /**
     * Evaluar si los atributos ungarded deben ser guardados en el Log
     * 
     * @return bool
     */
    public function shouldLogUnguarded()
    {
      if(!$this->activitylogOptions->logUnguarded){
        return false;
      }

      // This case means all of the attributes are guarded
      // so we'll not have any unguarded anyway.
      if(in_array('*', $this->getGuarded())){
        return false;
      }

      return true;
    }

    /**
     * Determines values that will be logged based on the difference.
     *
     * @return array
     **/
    public function attributeValuesToBeLogged(string $processingEvent)
    {
      // no loggable attributes, no values to be logged!
      if(!count($this->attributesToBeLogged())){
        return [];
      }

      $properties['attributes'] = static::logChanges(
        // if the current event is retrieved, get the model itself
        // else get the fresh default properties from database
        // as wouldn't be part of the saved model instance.
        $processingEvent == 'retrieved' ? $this : ($this->exists ? $this->fresh() ?? $this : $this)
      );

      if(static::eventsToBeRecorded()->contains('updated') && $processingEvent == 'updated'){
        // Fill the attributes with null values.
        $nullProperties = array_fill_keys(array_keys($properties['attributes']), null);

        // Populate the old key with keys from database and from old attributes.
        $properties['old'] = array_merge($nullProperties, $this->oldAttributes);

        // Fail safe.
        $this->oldAttributes = [];
      }

      if($this->activitylogOptions->logOnlyDirty && isset($properties['old'])){
        // Get difference between the old and new attributes.
        $properties['attributes'] = array_udiff_assoc(
          $properties['attributes'],
          $properties['old'],
          function($new, $old){
            // Strict check for php's weird behaviors
            if($old === null || $new === null){
              return $new === $old ? 0 : 1;
            }

            // Handels Date intervels comparsons since php cannot use spaceship
            // Operator to compare them and will throw ErrorException.
            if($old instanceof DateInterval){
              return CarbonInterval::make($old)->equalTo($new) ? 0 : 1;
            }elseif ($new instanceof DateInterval){
              return CarbonInterval::make($new)->equalTo($old) ? 0 : 1;
            }

            return $new <=> $old;
          }
        );

        $properties['old'] = collect($properties['old'])
          ->only(array_keys($properties['attributes']))
          ->all();
      }

      if(static::eventsToBeRecorded()->contains('deleted') && $processingEvent == 'deleted'){
        $properties['old'] = $properties['attributes'];
        unset($properties['attributes']);
      }

      return $properties;
    }

    /**
     * Obtener los cambios en el modelo
     * 
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return array
     */
    public static function logChanges(Model $model)
    {
      $changes = [];
      $attributes = $model->attributesToBeLogged();

      foreach($attributes as $attribute){
        if(Str::contains($attribute, '.')){
          $changes += self::getRelatedModelAttributeValue($model, $attribute);

          continue;
        }

        if(Str::contains($attribute, '->')){
          Arr::set(
            $changes,
            str_replace('->', '.', $attribute),
            static::getModelAttributeJsonValue($model, $attribute)
          );

          continue;
        }

        $changes[$attribute] = $model->getAttribute($attribute);

        if(is_null($changes[$attribute])){
          continue;
        }

        if($model->isDateAttribute($attribute)){
          $changes[$attribute] = $model->serializeDate(
            $model->asDateTime($changes[$attribute])
          );
        }

        if($model->hasCast($attribute)){
          $cast = $model->getCasts()[$attribute];

          if($model->isCustomDateTimeCast($cast)){
            $changes[$attribute] = $model->asDateTime($changes[$attribute])->format(explode(':', $cast, 2)[1]);
          }
        }
      }

      return $changes;
    }

    /**
     * Obtener el valor del atributo proporcionado del modelo relacionado
     * 
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string $attribute
     * @return array
     */
    protected static function getRelatedModelAttributeValue(Model $model, string $attribute)
    {
      $relatedModelNames = explode('.', $attribute);
      $relatedAttribute = array_pop($relatedModelNames);

      $attributeName = [];
      $relatedModel = $model;

      do{
        $attributeName[] = $relatedModelName = static::getRelatedModelRelationName($relatedModel, array_shift($relatedModelNames));

        $relatedModel = $relatedModel->$relatedModelName ?? $relatedModel->$relatedModelName();
      }while(! empty($relatedModelNames));

      $attributeName[] = $relatedAttribute;

      return [implode('.', $attributeName) => $relatedModel->$relatedAttribute ?? null];
    }

    /**
     * Obtener nombre de la relacion de modelo relacionado
     * 
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $relation
     * @return string
     */
    protected static function getRelatedModelRelationName(Model $model, string $relation)
    {
      return Arr::first([
        $relation,
        Str::snake($relation),
        Str::camel($relation),
      ], function (string $method) use ($model): bool{
        return method_exists($model, $method);
      }, $relation);
    }

    /**
     * Obtener el valor del atributo tipo json
     * 
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $attribute
     * @return mixed
     */
    protected static function getModelAttributeJsonValue(Model $model, string $attribute)
    {
      $path = explode('->', $attribute);
      $modelAttribute = array_shift($path);
      $modelAttribute = collect($model->getAttribute($modelAttribute));

      return data_get($modelAttribute, implode('.', $path));
    }
}
