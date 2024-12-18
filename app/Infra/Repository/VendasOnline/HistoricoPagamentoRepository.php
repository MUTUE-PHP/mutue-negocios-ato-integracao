<?php

namespace App\Infra\Repository\VendasOnline;

use App\Models\empresa\HistoricoPagamentosVendasOnline;

class HistoricoPagamentoRepository
{

    public function atualizarHistoricoPagamento($pagamentoId, $statusPagamentoId, $descricao){
        return HistoricoPagamentosVendasOnline::create([
            'pagamento_id' => $pagamentoId,
            'status_pagamento_id' => $statusPagamentoId,
            'descricao' => $descricao,
            'user_id' => auth()->user()->id,
        ]);
    }
    public function getHistoricoPagamentoOnline(){
        return HistoricoPagamentosVendasOnline::get();
    }

}
