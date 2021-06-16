<?php

namespace App\Casts\Sii;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;        

class Representante implements CastsAttributes
{
    /**
     * Estructura por defecto
     * 
     * @var array
     */
    private $defaults = [
      'id' => null,
      'rut' => null,
      'password' => null,
      'certificatePassword' => null,
      'vencimiento_certificado' => null,
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
      $data = json_decode(is_null($value) ? json_encode($this->defaults) : $value);
      $data->vencimiento_certificado = $data->vencimiento_certificado ? new Carbon($data->vencimiento_certificado) : null;

      return $data;
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
      $value['certificatePassword'] = Crypt::encryptString($value['certificatePassword']);

      return json_encode($value);
    }
}
