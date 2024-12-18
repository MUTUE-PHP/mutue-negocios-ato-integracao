<?php

namespace App\Http\Middleware;

use App\Application\UseCase\Admin\Anuncios\GetAnuncios;
use App\Application\UseCase\Admin\Anuncios\GetAnunciosPorDataValidas;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Closure;

class Anuncio
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $getAnunciosPorDataValidas = new GetAnunciosPorDataValidas(new DatabaseRepositoryFactory());
        $anuncios = $getAnunciosPorDataValidas->execute();
        view()->share('anuncios', $anuncios);
        return $next($request);
    }
}
