<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class HistoricoPagamentosVendasOnline extends Model
{

    protected $connection = 'mysql2';
    protected $table ='historico_pagamentos_vendas_online';
    protected $fillable = [
        'pagamento_id',
        'status_pagamento_id',
        'descricao',
        'user_id',
    ];

}
