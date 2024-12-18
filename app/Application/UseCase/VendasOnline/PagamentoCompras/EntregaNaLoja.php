<?php

namespace App\Application\UseCase\VendasOnline\PagamentoCompras;

class EntregaNaLoja implements TipoEntregaInterface {
    public function getTaxaEntrega() {
        return 0.0; // Não há taxa de entrega para entrega na loja
    }
}
