<?php

namespace App;

use Illuminate\Database\Eloquent\{Model, Builder};
use Illuminate\Support\{Collection, Str};
use Illuminate\Support\Facades\Auth;
use App\{Empleado, Postulante};
use App\Scopes\EmpresaWithGlobalScope;

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
    private static $_reserved = [
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
      '{{e_nombre_del_contrato_principal}}',
      '{{e_valor_del_contrato_principal}} ',
      '{{e_fecha_de_inicio_del_contrato_principal}} ',
      '{{e_fecha_de_fin_del_contrato_principal}}',
      '{{e_faena_del_contrato_principal}}',
      '{{e_descripcion_del_contrato_principal}}',
      '{{e_sueldo_del_contrato_de_empleado}}',
      '{{e_fecha_de_inicio_del_contrato_de_empleado}}',
      '{{e_fecha_de_fin_del_contrato_de_empleado}}',
      '{{e_jornada_del_contrato_de_empleado}}',
      '{{e_fecha_de_inicio_de_jornada_del_contrato_de_empleado}}',
      '{{e_descripcion_del_contrato_de_empleado}}',
      '{{p_nombres}}',
      '{{p_apellidos}}',
      '{{p_rut}}',
      '{{p_telefono}}',
      '{{p_email}}',
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
      static::addGlobalScope(new EmpresaWithGlobalScope);
    }

    /**
     * Incluir solo los registros globales (Que no pertenecen a una Empresa).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGlobal($query)
    {
      return $query->whereNull('empresa_id');
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
        case 'postulante':
          $tipo = 'Postulante';
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
        $keepChecking = self::where('variable', $nombre)
          ->when($id, function($query, $id){
            return $query->where('id', '!=', $id);
          })
          ->exists();
        $count += $keepChecking ? 1 : 0;
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
      return $this->tipo == 'empleado' || $this->tipo == 'postulante' || self::isReserved($this->variable);
    }

    /**
     * Obtener las variables estaticas con sus valores segun el modelo especificado
     *
     * @param  \App\Empleado|\App\Postulante  $model
     * @return array
     */
    public static function mappedVariablesToAttributes($model)
    {
      $method = ($model instanceof Empleado ? 'empleado' : 'postulante').'Variables';

      return self::{$method}($model);
    }

    /**
     * Obtener las variables del Empleado
     *
     * @param  \App\Empleado  $empleado
     * @return array
     */
    private static function empleadoVariables(Empleado $empleado)
    {
      $empleado->load([
        'usuario',
        'banco',
        'contrato.faena',
      ]);

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
        '{{e_nombre_del_contrato_principal}}' => $empleado->contrato->nombre,
        '{{e_valor_del_contrato_principal}}' => $empleado->contrato->valor,
        '{{e_fecha_de_inicio_del_contrato_principal}}' => $empleado->contrato->inicio,
        '{{e_fecha_de_fin_del_contrato_principal}}' => $empleado->contrato->fin,
        '{{e_faena_del_contrato_principal}}' => optional($empleado->contrato->faena)->nombre,
        '{{e_descripcion_del_contrato_principal}}' => $empleado->contrato->descripcion,
        '{{e_sueldo_del_contrato_de_empleado}}' => optional($empleado->lastContrato)->sueldo(),
        '{{e_fecha_de_inicio_del_contrato_de_empleado}}' => optional($empleado->lastContrato)->inicio,
        '{{e_fecha_de_fin_del_contrato_de_empleado}}' => optional($empleado->lastContrato)->fin,
        '{{e_jornada_del_contrato_de_empleado}}' => optional($empleado->lastContrato)->jornada,
        '{{e_fecha_de_inicio_de_jornada_del_contrato_de_empleado}}' => optional($empleado->lastContrato)->inicio_jornada,
        '{{e_descripcion_del_contrato_de_empleado}}' => optional($empleado->lastContrato)->descripcion,
      ];
    }

    /**
     * Obtener las variables del Postulante
     *
     * @param  \App\Postulante  $postulante
     * @return array
     */
    private static function postulanteVariables(Postulante $postulante)
    {
      return [
        '{{p_nombres}}' => $postulante->nombres,
        '{{p_apellidos}}' => $postulante->apellidos,
        '{{p_rut}}' => $postulante->rut,
        '{{p_telefono}}' => $postulante->telefono,
        '{{p_email}}' => $postulante->email,
      ];
    }

    /**
     * Obtener las variables reservadas para el sistema
     * 
     * @return array
     */
    public static function getReservedVariables()
    {
      return self::$_reserved;
    }

    /**
     * Evaluar si la variable proporcionada, esta reservada para el sistema
     * 
     * @param  string $variable
     * @return bool
     */
    public static function isReserved($variable)
    {
      return in_array($variable, self::getReservedVariables());
    }

    /**
     * Obtener las variables como array para los tokens del editor de texto (CKEditor)
     *
     * @param  Illuminate\Support\Collection|null  $variables
     * @return array
     */
    public static function toEditor(Collection $variables = null)
    {
      $variables = $variables ?? self::all();
      return $variables
      ->map(function ($variable) {
        return [$variable->nombre, $variable->withoutTokens()];
      })->toArray();
    }
}
