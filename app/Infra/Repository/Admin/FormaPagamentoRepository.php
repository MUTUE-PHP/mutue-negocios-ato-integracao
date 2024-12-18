<?php

namespace App\Infra\Repository\Admin;

use App\Models\admin\FormaPagamento as FormaPagamentoDatabase;
use App\Models\empresa\FormaPagamentoGeral;

class FormaPagamentoRepository
{
    public function getFormaPagamento($formaPagamentoId)
    {
        return FormaPagamentoDatabase::where('id', $formaPagamentoId)->first();
    }
    public function getFormasPagamentosMV(){
        return FormaPagamentoGeral::whereIn('id', [4, 5])->get();
    }
    public function getFormasPagamentos()
    {
        return FormaPagamentoGeral::get();
    }
    public function getFormasPagamento($id){
        return FormaPagamentoGeral::where('id', $id)->first();
    }
}
