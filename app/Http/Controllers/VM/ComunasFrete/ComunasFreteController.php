<?php

namespace App\Http\Controllers\VM\ComunasFrete;
use App\Application\UseCase\VendasOnline\ComunasFrete\GetComunasFrete;
use App\Application\UseCase\VendasOnline\ComunasFrete\GetComunasFretePeloMunicipio;
use App\Application\UseCase\VendasOnline\ComunasFrete\GetComunasFreteSemPaginacao;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\GetMunicipiosFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\GetMunicipiosFretePelaProvincia;
use App\Http\Controllers\Controller;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use Illuminate\Http\Request;

class ComunasFreteController extends Controller
{
    public function listarComunasFretePeloMunicipio($municipioId){
        $getComunasFrete = new GetComunasFretePeloMunicipio(new DatabaseRepositoryFactory());
        $output =  $getComunasFrete->execute($municipioId);

        return response()->json([
            'data' => $output,
            'message'=> 'lista de fretes por comunas'
        ]);
    }
    public function listarComunasFreteSemPaginacao(Request $request){

        $getComunasFrete = new GetComunasFreteSemPaginacao(new DatabaseRepositoryFactory());
        $output =  $getComunasFrete->execute($request);
        return response()->json([
            'data' => $output,
            'message'=> 'lista de fretes por comunas'
        ]);
    }
}
