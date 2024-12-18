<?php

namespace App\Http\Controllers\Api\FormaPagamentos;
use App\Http\Controllers\Controller;
use App\Infra\Repository\Admin\FormaPagamentoRepository;

class FormaPagamentoIndexController extends Controller
{

    private $formasPagamentoRepository;

    public function __construct(FormaPagamentoRepository $formasPagamentoRepository)
    {
        $this->formasPagamentoRepository = $formasPagamentoRepository;
    }

    public function listarFormasPagamentos()
    {
        $data['formapagamentos'] = $this->formasPagamentoRepository->getFormasPagamentos();
        return response()->json($data, 200);
    }

}
