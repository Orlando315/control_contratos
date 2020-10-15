<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\EmpresaScope;
use Illuminate\Support\Str;

class PlantillaVariable extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plantillas_variables';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'tipo', 'variable'];

    /**
     * Variables reservadas para las variables estaticas del sistema
     * 
     * @var array
     */
    private $_reserved = [
      '{{e_nombres}}',
      '{{e_apellidos}}',
      '{{e_rut}}',
      '{{e_fecha_de_nacimiento}}',
      '{{e_telefono}}',
      '{{e_email}}',
      '{{e_direccion}}',
      '{{e_profesion}}',
      '{{e_sexo}}',
      '{{e_talla_camisa}}',
      '{{e_talla_zapato}}',
      '{{e_talla_pantalon}}',
      '{{e_nombre_contacto_de_emergencia}}',
      '{{e_telefono_contacto_de_emergencia}}',
      '{{e_nombre_del_banco}}',
      '{{e_tipo_de_cuenta_del_banco}}',
      '{{e_cuenta_del_banco}}',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
      parent::boot();

      static::saving(function ($variable) {
        $variable->setVariableName();
      });

      static::addGlobalScope(new EmpresaScope);
    }

    /**
     * Obtener las variables como array para los tokens del editor de texto (CKEditor)
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeToFormEditor()
    {
      return self::select('nombre', 'variable')
                  ->get()
                  ->map(function ($variable) {
                    return [$variable->nombre, $variable->withoutTokens()];
                  })->toArray();
    }

    /**
     * Obtener la variable sin los tokens al inicio y final
     *
     * @return string
     */
    public function withoutTokens()
    {
      return substr($this->variable, 2, -2);
    }

    /**
     * Obtener el atributo formateado
     *
     * @param string
     */
    public function tipo()
    {
      switch ($this->tipo) {
        case 'empleado':
          $tipo = 'Empleado';
          break;
        case 'rut':
          $tipo = 'RUT';
          break;
        case 'date':
          $tipo = 'Fecha';
          break;
        case 'tel':
          $tipo = 'Télefono';
          break;
        case 'number':
          $tipo = 'Numeros';
          break;
        case 'firma':
          $tipo = 'Firma';
          break;
        case 'text':
          $tipo = 'Texto';
        default:
          $tipo = 'Texto';
          break;
      }

      return $tipo;
    }

    /**
     * Establecer el key de la variable basado en el nombre
     */
    public function setVariableName()
    {
      $variable = Str::slug($this->nombre, '_');
      $count = 0;
      $id = $this->id ?? false;
      $keepChecking = true;

      while($keepChecking){
        $nombre = '{{'.($count < 1 ? $variable : $variable.'_'.$count).'}}';

        // Evaluar si existe en la base de datos
        $isRegistered = self::where('variable', $nombre)
        ->when($id, function($query, $id){
          return $query->where('id', '!=', $id);
        })
        ->exists();

        // Evaluar si es una variable reservada
        $isReserved = in_array($nombre, $this->_reserved);

        $count++;
        $keepChecking = $isRegistered || $isReserved;
      }

      $this->variable = '{{'.($count < 1 ? $variable : $variable.'_'.$count).'}}';
    }

    /**
     * Evaluar si la Variable es estatica
     *
     * @return  bool
     */
    public function isStatic()
    {
      return $this->tipo == 'empleado';
    }

    /**
     * Obtener las variables estaticas con sus valores
     *
     * @param  \App\Empleado $empleado
     * @return array
     */
    public static function mappedVariablesToAttributes(Empleado $empleado)
    {
      return [
        '{{e_nombres}}' => $empleado->usuario->nombres,
        '{{e_apellidos}}' => $empleado->usuario->apellidos,
        '{{e_rut}}' => $empleado->usuario->rut,
        '{{e_fecha_de_nacimiento}}' => $empleado->fecha_nacimiento,
        '{{e_telefono}}' => $empleado->usuario->telefono,
        '{{e_email}}' => $empleado->usuario->email,
        '{{e_direccion}}' => $empleado->direccion,
        '{{e_profesion}}' => $empleado->profesion,
        '{{e_sexo}}' => $empleado->sexo,
        '{{e_talla_camisa}}' => $empleado->talla_camisa,
        '{{e_talla_zapato}}' => $empleado->talla_zapato,
        '{{e_talla_pantalon}}' => $empleado->talla_pantalon,
        '{{e_nombre_contacto_de_emergencia}}' => $empleado->nombre_emergencia,
        '{{e_telefono_contacto_de_emergencia}}' => $empleado->telefono_emergencia,
        '{{e_nombre_del_banco}}' => $empleado->banco->nombre,
        '{{e_tipo_de_cuenta_del_banco}}' => $empleado->banco->tipo_cuenta,
        '{{e_cuenta_del_banco}}' => $empleado->banco->cuenta,
      ];
    }
}
