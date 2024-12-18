<?php

namespace App\Http\Controllers\empresa\Faturacao;
use App\Application\UseCase\Empresa\Faturacao\SimuladorFaturacao;
use App\Http\Controllers\Controller;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Http\Request;

class EmitirDocumentoController extends Controller
{
    public function store(Request $request){
        $simuladorFaturacao = new SimuladorFaturacao(new DatabaseRepositoryFactory());
        $fatura = $simuladorFaturacao->execute($request);
    }
}
