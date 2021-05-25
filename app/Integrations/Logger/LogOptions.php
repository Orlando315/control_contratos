<?php

namespace App\Integrations\Logger;

use Closure;

class LogOptions
{
    public ?string $logName = 'empresa';

    public bool $submitEmptyLogs = false;

    public bool $logFillable = true;

    public bool $logOnlyDirty = true;

    public bool $logUnguarded = false;

    public array $logAttributes = [];

    public array $logExceptAttributes = [];

    public array $logAditionalAttributes = [];

    public array $dontLogIfAttributesChangedOnly = [];

    public ?Closure $descriptionForEvent = null;

    /**
     * Start configuring model with the default options.
     */
    public static function defaults()
    {
      return new static();
    }

    /**
     * Log all attributes on the model.
     */
    public function logAll()
    {
      return $this->logOnly(['*']);
    }

    /**
     * log changes to all the $guarded attributes of the model.
     */
    public function logUnguarded()
    {
      $this->logUnguarded = true;

      return $this;
    }

    /**
     * log changes to all the $fillable attributes of the model.
     */
    public function logFillable()
    {
      $this->logFillable = true;

      return $this;
    }

    /**
     * Stop logging $fillable attributes of the model.
     */
    public function dontLogFillable()
    {
      $this->logFillable = false;

      return $this;
    }

    /**
     * Log changes that has actually changed after the update.
     */
    public function logOnlyDirty()
    {
      $this->logOnlyDirty = true;

      return $this;
    }

    /**
     * Log changes only if these attributes changed.
     */
    public function logOnly(array $attributes)
    {
      $this->logAttributes = $attributes;

      return $this;
    }

    /**
     * Log these aditional attributes when changed.
     */
    public function logAditionalAttributes(array $attributes)
    {
      $this->logAditionalAttributes = $attributes;

      return $this;
    }

    /**
     * Exclude these attributes from being logged.
     */
    public function logExcept(array $attributes)
    {
      $this->logExceptAttributes = $attributes;

      return $this;
    }

    /**
     * Don't trigger an activity if these attributes changed logged.
     */
    public function dontLogIfAttributesChangedOnly(array $attributes)
    {
      $this->dontLogIfAttributesChangedOnly = $attributes;

      return $this;
    }

    /**
     * Dont store empty logs. Storing empty logs can happen when you only
     * want to log a certain attribute but only another changes.
     */
    public function dontSubmitEmptyLogs()
    {
      $this->submitEmptyLogs = false;

      return $this;
    }

    /**
     * Allow storing empty logs. Storing empty logs can happen when you only
     * want to log a certain attribute but only another changes.
     */
    public function submitEmptyLogs()
    {
      $this->submitEmptyLogs = true;

      return $this;
    }

    /**
     * Customize log name.
     */
    public function useLogName(string $logName)
    {
      $this->logName = $logName;

      return $this;
    }

    /**
     * Customize log description using callback.
     */
    public function setDescriptionForEvent(Closure $callback)
    {
      $this->descriptionForEvent = $callback;

      return $this;
    }
}
