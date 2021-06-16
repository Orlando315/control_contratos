<?php

namespace App\Casts\Sii;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Crypt;

class Account implements CastsAttributes
{
    /**
     * Estructura por defecto
     * 
     * @var array
     */
    private $defaults = [
      'id' => null,
      'username' => null,
      'email' => null,
      'password' => null,
    ];

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, $key, $value, $attributes)
    {
      $value = is_null($value) ? json_encode($this->defaults) : $value;

      return json_decode($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
      $value = (array) $value;
      $value['password'] = Crypt::encryptString($value['password']);

      return json_encode($value);
    }
}
