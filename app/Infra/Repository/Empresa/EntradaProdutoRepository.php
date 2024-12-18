<?php

namespace App\Infra\Repository\Empresa;

use App\Domain\Entity\Empresa\EntradaProduto\EntradaProduto;
use App\Models\empresa\EntradaStock as EntradaStockDatabase;

class EntradaProdutoRepository
{
    public function fazerEntradaProduto(EntradaProduto $entradaProduto)
    {
        return EntradaStockDatabase::create([
            'data_factura_fornecedor' => $entradaProduto->getDataFaturaFornecedor(),
            'fornecedor_id' => $entradaProduto->getFornecedorId(),
            'empresa_id' => auth()->user()->empresa_id ?? 53,
            'forma_pagamento_id' => $entradaProduto->getFormaPagamentoId(),
            'tipo_user_id' => 3,//Empresa
            'num_factura_fornecedor' => $entradaProduto->getNumeracaoFatura(),
            'descricao' => $entradaProduto->getDescricao(),
            'total_compras' => $entradaProduto->getTotalCompras(),
            'totalSemImposto' => $entradaProduto->getTotalSemImposto(),
            'total_venda' => $entradaProduto->getTotalVenda(),
            'total_iva' => $entradaProduto->getTotalTaxaIva(),
            'total_desconto' => $entradaProduto->getTotalDesconto(),
            'total_retencao' => $entradaProduto->getTotalRetencao(),
            'user_id' => auth()->user()->id ?? 35,
            'canal_id' => 2,
            'status_id' => 1,
            'statusPagamento' => $entradaProduto->getStatusPagamento(),
            'armazem_id' => $entradaProduto->getArmazemId(),
            'totalLucro' => $entradaProduto->getTotalLucro(),
            'operador' => auth()->user()->name ?? 'Mutue mobile'
        ]);
    }
}
