<?php

namespace App\Http\Controllers\VM\MunicipiosFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\GetMunicipiosFrete;
use App\Application\UseCase\VendasOnline\MunicipiosFrete\GetMunicipiosFretePelaProvincia;
use App\Http\Controllers\Controller;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use Illuminate\Http\Request;

class MuniciposFreteController extends Controller
{
    public function listarMunicipiciosFretePelaPronvicia($provinciaId){
        $getMunicipiosFrete = new GetMunicipiosFretePelaProvincia(new DatabaseRepositoryFactory());
        $output =  $getMunicipiosFrete->execute($provinciaId);

        return response()->json([
            'data' => $output,
            'message'=> 'lista de fretes por municipios'
        ]);
    }
    public function listarMunicipiciosFrete(Request $request){

        $getMunicipiosFrete = new GetMunicipiosFrete(new DatabaseRepositoryFactory());
        $output =  $getMunicipiosFrete->execute($request);
        return response()->json([
            'data' => $output,
            'message'=> 'lista de fretes por municipios'
        ]);
    }
}
