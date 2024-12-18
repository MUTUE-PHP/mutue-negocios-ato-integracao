<?php

namespace App\Http\Middleware;

use App\Models\admin\Empresa;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;

class UpdateLastAccess
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            Empresa::where('referencia', $user->empresa->referencia)->update([
                'ultimo_acesso' => Carbon::now()
            ]);
        }
        return $next($request);
    }
}
