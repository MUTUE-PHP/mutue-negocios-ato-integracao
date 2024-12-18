<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class ExtratoCartaoCliente extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'historico_extrato_cartao_cliente';

    protected $fillable = [
        'id',
        'clienteId',
        'bonus',
        'operacao',
        'saldo_anterior',
        'saldo_atual',
        'valorBonus',
        'valorDescontarCartao',
        'userId',
        'documetoReferente',
        'created_at',
        'updated_at',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'clienteId');
    }
}
