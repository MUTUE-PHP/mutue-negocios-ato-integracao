<?php

namespace App\Application\UseCase\VendasOnline\PagamentoCompras;

use App\Infra\Repository\VendasOnline\ComunasFreteRepository;

class EntregaEmCasa implements TipoEntregaInterface {


    private $comunaId;
    private ComunasFreteRepository $comunaRepository;

    public function __construct($comunaRepository, $comunaId)
    {
        $this->comunaId = $comunaId;
        $this->comunaRepository = $comunaRepository;
    }

    public function getTaxaEntrega() {
        return $this->comunaRepository->getComunaFrete($this->comunaId)['valor_entrega'];
    }
}
