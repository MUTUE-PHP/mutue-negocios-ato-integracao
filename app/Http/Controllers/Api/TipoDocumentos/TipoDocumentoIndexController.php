<?php

namespace App\Http\Controllers\Api\TipoDocumentos;
use App\Http\Controllers\Controller;
use App\Infra\Repository\Admin\FormaPagamentoRepository;
use App\Infra\Repository\Admin\TipoDocumentoRepository;

class TipoDocumentoIndexController extends Controller
{

    private $tipoDocumentoRepository;

    public function __construct(TipoDocumentoRepository $tipoDocumentoRepository)
    {
        $this->tipoDocumentoRepository = $tipoDocumentoRepository;
    }

    public function listarTiposDocumentos()
    {
        return $this->tipoDocumentoRepository->getTiposDocumentos();
    }

}
