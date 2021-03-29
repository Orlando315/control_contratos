<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function auth(Request $request)
    {
      $this->validate($request, [
        'usuario' =>'required',
        'password' => 'required',
      ]);

      if(Auth::attempt($request->only(['usuario', 'password']))){
        return redirect()->intended('dashboard');
      }

      return redirect()->route('login.view')->withErrors('¡Combinación de usuario y clave incorrecta!');
    }

    public function logout()
    {
      Auth::logout();
      return redirect()->route('login.view');
    }
}
