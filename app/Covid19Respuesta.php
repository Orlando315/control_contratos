<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Anticipo;
use App\Scopes\EmpresaScope;

class Covid19Respuesta extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'covid19_respuestas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'empresa_id',
      'respuestas',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
      'respuestas' => 'array',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
      parent::boot();
      static::addGlobalScope(new EmpresaScope);
    }

    /**
     * User al que pertenece la respuesta
     */
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    /**
     * Obtener la respuesta a la pregunta proporcionada
     * 
     * @param  int  $pregunta
     * @return string
     */
    public function getRespuesta($pregunta)
    {
      $respuesta = array_key_exists($pregunta, $this->respuestas) ? ($this->respuestas[$pregunta] == '1') : false;

      return $respuesta ? '<small class="label label-primary">Sí</small>' : '<small class="label label-default">No</small>';
    }

    /**
     * Obtener los años de las respuestas a la encuesta covid-19
     */
    public static function allYears()
    {
      return self::selectRaw('YEAR(created_at) as year')->distinct()->orderBy('year', 'desc');
    }

    /**
     * Obtener los meses que tienen respuestas registradas en el año proporcionado
     * 
     * @param  int  $year
     */
    public static function getMonthsByYear($year)
    {
      return self::selectRaw('MONTH(created_at) as month')->distinct()->whereYear('created_at', $year)->orderBy('month', 'desc');
    }

    /**
     * Obtener las respuestas a la encuesta covid-19 agrouadas por mes, del año proporcionado
     *
     * @param  int  $year
     * @return array
     */
    public static function monthlyGroupedByYear($year)
    {
      $months = self::getMonthsByYear($year)
        ->get()
        ->pluck('month')
        ->toArray();

      $respuestasByMonth = [];

      foreach($months as $month){
        $respuestas = self::with('user')
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->orderBy('created_at', 'desc')
        ->get();

        $dataMonth = [
          'month' => $month,
          'title' => ucfirst(Anticipo::getMonthName($month)),
          'respuestas' => $respuestas,
        ];
        $respuestasByMonth[] = (object)$dataMonth;
      }

      return $respuestasByMonth;
    }

    /**
     * Evaluar si la encuesta tiene respuestas postivas
     *
     * @param  bool  $returnCount
     * @return bool|int
     */
    public function hasPostiveAnswer($returnCount = false)
    {
      $filtered = collect($this->respuestas)->reject(function ($value, $key) {
        return $value == 0;
      })
      ->count();

      return $returnCount ? $filtered : ($filtered > 0);
    }

    /**
     * Obtener el atributo formateado
     * 
     * @return string
     */
    public function positiveAnswers()
    {
      $count = $this->hasPostiveAnswer(true);
      return ($count > 0) ? '<span class="label label-primary">Sí ('.$count.')</span>' : '<span class="label label-default">No</span>';
    }
}
