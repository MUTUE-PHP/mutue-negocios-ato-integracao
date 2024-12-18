<?php

namespace App\Infra\Repository\VendasOnline;
use App\Models\empresa\Factura as FacturaDatabase;
use Illuminate\Support\Facades\DB;

class FaturaRepository
{

    public function salvar($pagamentoVendaOnline){

//        $fatura = FacturaDatabase::create([
//            'total_preco_factura' => $pagamentoVendaOnline['totalPagamento'],
//            ''
//
//        ]);
        return $pagamentoVendaOnline;
    }

}
