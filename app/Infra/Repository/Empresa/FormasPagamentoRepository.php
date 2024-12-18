<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\FormaPagamentoGeral as FormaPagamentoDatabase;

class FormasPagamentoRepository
{
    public function getFormasPagamentos(){
        return FormaPagamentoDatabase::get();
    }
    public function getFormasPagamentosSemDuplo(){
        return FormaPagamentoDatabase::where('id', '!=', 6)->get();

    }
    public function getFormaPagamentoEmitirRecibo(){
        return FormaPagamentoDatabase::whereIn('id', [1, 3, 4, 5])->get();
    }
    public function getFormasPagamentosByFaturacao(){
        return FormaPagamentoDatabase::whereIn('id', [1, 3, 4, 5])->get();
    }
}
