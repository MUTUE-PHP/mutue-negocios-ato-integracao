<?php

namespace App\Http\Controllers\Api\FormaPagamentos;
use App\Http\Controllers\Controller;
use App\Infra\Repository\Admin\FormaPagamentoRepository;

class MVFormaPagamentosMutueController extends Controller
{

    private $formasPagamentosRepository;

    public function __construct(FormaPagamentoRepository $formasPagamentosRepository)
    {
        $this->formasPagamentosRepository = $formasPagamentosRepository;
    }

    public function listarFormasPagamentos(){
        return $this->formasPagamentosRepository->getFormasPagamentosMV();
    }
    public function listarFormasPagamento($id){
        return $this->formasPagamentosRepository->getFormasPagamento($id);
    }
}
