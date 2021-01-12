<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Ayuda;

class AyudaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $ayudas = Auth::user()->role()->ayudas()->active()->get();

      return view('ayuda.index', compact('ayudas'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ayuda  $ayuda
     * @return \Illuminate\Http\Response
     */
    public function show(Ayuda $ayuda)
    {
      $this->authorize('view', $ayuda);

      return view('ayuda.show', compact('ayuda'));
    }
}
