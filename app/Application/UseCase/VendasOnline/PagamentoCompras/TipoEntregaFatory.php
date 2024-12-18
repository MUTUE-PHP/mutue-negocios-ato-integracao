<?php

namespace App\Application\UseCase\VendasOnline\PagamentoCompras;

class TipoEntregaFatory
{
    private $instanciaEntrega;
    private $tipoEntrega;

    public function __construct($tipoEntregaId, $comunaId = null, $comunaRepository)
    {
        $this->tipoEntrega = $tipoEntregaId;
        switch ($tipoEntregaId){
            case 1:
                $this->instanciaEntrega = new EntregaEmCasa($comunaRepository, $comunaId);
                break;
            case 2:
                $this->instanciaEntrega = new EntregaNaLoja();
                break;
            default:
                throw new \Exception("Tipo de entrega não encontrado");
                break;
        }
    }
    public function getInstanciaEntrega(){
        return $this->instanciaEntrega;
    }
    public function getTipoEntrega(){
        return $this->tipoEntrega;
    }

}
