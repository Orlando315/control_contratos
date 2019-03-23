<?php

namespace App\Http\Controllers;

use App\Ayuda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AyudasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ayudas = Ayuda::with('usuario')->get();

        return view('ayudas.index', ['ayudas' => $ayudas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ayudas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
          'titulo' => 'required|string',
          'contenido' => 'nullable|string',
          'video' => 'nullable|string',
        ]);

        $ayuda = new Ayuda($request->all());

        if(Auth::user()->ayudas()->save($ayuda)){

          return redirect('ayudas/' . $ayuda->id)->with([
            'flash_message' => 'Ayuda agregada exitosamente.',
            'flash_class' => 'alert-success'
            ]);
        }else{
          return redirect('ayudas/create')->with([
            'flash_message' => 'Ha ocurrido un error.',
            'flash_class' => 'alert-danger',
            'flash_important' => true
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ayuda  $ayuda
     * @return \Illuminate\Http\Response
     */
    public function show(Ayuda $ayuda)
    {
        return view('ayudas.show', ['ayuda' => $ayuda]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ayuda  $ayuda
     * @return \Illuminate\Http\Response
     */
    public function edit(Ayuda $ayuda)
    {
        return view('ayudas.edit', ['ayuda' => $ayuda]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ayuda  $ayuda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ayuda $ayuda)
    {
        $this->validate($request, [
          'titulo' => 'required|string',
          'contenido' => 'nullable|string',
          'video' => 'nullable|string',
        ]);

        $ayuda->fill($request->all());

        if($ayuda->save()){
          return redirect('ayudas/' . $ayuda->id)->with([
            'flash_message' => 'Ayuda modificada exitosamente.',
            'flash_class' => 'alert-success'
            ]);
        }else{
          return redirect('ayudas/' . $ayuda->id)->with([
            'flash_message' => 'Ha ocurrido un error.',
            'flash_class' => 'alert-danger',
            'flash_important' => true
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ayuda  $ayuda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ayuda $ayuda)
    {
        if($ayuda->delete()){

          return redirect('ayudas')->with([
            'flash_class'   => 'alert-success',
            'flash_message' => 'Ayuda eliminada exitosamente.'
          ]);
        }else{
          return redirect('ayudas/' . $ayuda->id)->with([
            'flash_class'     => 'alert-danger',
            'flash_message'   => 'Ha ocurrido un error.',
            'flash_important' => true
          ]);
        }
    }

    public function list()
    {
      $ayudas = Ayuda::all();

      return view('ayudas.list', ['ayudas' => $ayudas]);
    }
}
