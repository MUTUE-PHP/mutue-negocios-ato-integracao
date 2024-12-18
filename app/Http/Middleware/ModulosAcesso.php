<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class ModulosAcesso
{

    public function handle(Request $request, Closure $next)
    {
        $modulo = DB::table('users_modulos')
            ->where('user_id', Auth::user()->id)
            ->where('modulo_id', 1)->first();
        if($modulo){
            return $next($request);
        }
        return response()->redirectToRoute('modulosAcesso.index');
    }
}
