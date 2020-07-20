<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
      if(! ($role == 'staff' ? $request->user()->isStaff() : $request->user()->checkRole($role))){
        return redirect('dashboard');
      }

      return $next($request);
    }
}
