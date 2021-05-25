<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Log;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->authorize('viewAny', Log::class);

      $logs = Log::with(['user', 'subject'])->simplePaginate(100);

      return view('admin.log.index', compact('logs'));
    }
}
