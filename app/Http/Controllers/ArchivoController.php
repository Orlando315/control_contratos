<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Carpeta;
use App\Documento;

class ArchivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $carpetas = Auth::user()->hasPermission('archivo-view') ?
      Carpeta::archivo()->main()->get() :
      Carpeta::archivo()->main()->where(function (Builder $where) {
        $where->public()
        ->orWhereHas('archivoUsers', function (Builder $query) {
          $query->where('users.id', Auth::id());
        });
      })
      ->get();

      $documentos = Auth::user()->hasPermission('archivo-view') ?
      Documento::archivo()->main()->get() :
      Documento::archivo()->main()->where(function (Builder $where) {
        $where->public()
        ->orWhereHas('archivoUsers', function (Builder $query) {
          $query->where('users.id', Auth::id());
        });
      });

      return view('archivo.index', compact('carpetas', 'documentos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Carpeta  $carpeta
     * @return \Illuminate\Http\Response
     */
    public function carpeta(Carpeta $carpeta)
    {
      $this->authorize('viewArchivoCarpeta', $carpeta);

      $carpeta->load([
        'subcarpetas',
        'documentos',
        'archivoUsers',
      ]);

      return view('archivo.carpeta', compact('carpeta'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Documento  $documento
     * @return \Illuminate\Http\Response
     */
    public function download(Documento $documento)
    {
      $this->authorize('downloadArchivoDocumento', $documento);

      if(!Storage::exists($documento->path)){
        abort(404);
      }

      return Storage::download($documento->path, $documento->nombre.'.'.$documento->getExtension());
    }
}
