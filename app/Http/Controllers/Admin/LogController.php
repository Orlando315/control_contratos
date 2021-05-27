<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Log;
use App\User;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $this->authorize('viewAny', Log::class);

      $logs = Log::with(['user', 'subject'])
      ->when($request->user, function ($query, $user) {
        return $query->where('user_id', $user);
      })
      ->when($request->model, function ($query, $model) {
        return $query->where('subject_type', $model);
      })
      ->when($request->event, function ($query, $event) {
        return $query->where('event', $event);
      })
      ->when($request->from, function ($query, $from) {
        try{
          return $query->where('created_at', '>=', (new Carbon($from))->startOfDay());
        }catch(\Exception $e){
        }
      })
      ->when($request->to, function ($query, $to) {
        try{
          return $query->where('created_at', '<=', (new Carbon($to))->endOfDay());
        }catch(\Exception $e){
        }
      })
      ->simplePaginate(100);

      $models = Log::getLoggedModels();
      $users = User::all();

      return view('admin.log.index', compact('logs', 'users', 'models'));
    }
}
