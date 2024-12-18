<?php

namespace App\Infra\Repository\Empresa;
use App\Models\empresa\ExtratoCartaoCliente as ExtratoCartaoClienteDatabase;

use App\Domain\Entity\Empresa\CartaoCliente\ExtratoCartaoCliente;
use Carbon\Carbon;

class ExtratoCartaoClienteRepository
{

    public function atualizar(ExtratoCartaoCliente $extratoCartaoCliente)
    {
        return ExtratoCartaoClienteDatabase::where('documetoReferente', $extratoCartaoCliente->getDocumetoReferente())->update([
            'valorBonus' => $extratoCartaoCliente->getValorBonus(),
            'saldo_atual' => $extratoCartaoCliente->getSaldoAtual()
        ]);
    }

    public function salvar(ExtratoCartaoCliente $extratoCartaoCliente)
    {
        return ExtratoCartaoClienteDatabase::create([
            'clienteId' => $extratoCartaoCliente->getClienteId(),
            'bonus' => $extratoCartaoCliente->getBonus(),
            'saldo_anterior' => $extratoCartaoCliente->getSaldoAnterior(),
            'saldo_atual' => $extratoCartaoCliente->getSaldoAtual(),
            'valorBonus' => $extratoCartaoCliente->getValorBonus(),
            'valorDescontarCartao' => $extratoCartaoCliente->getValorDescontarCartao(),
            'userId' => auth()->user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'documetoReferente' => $extratoCartaoCliente->getDocumetoReferente(),
        ]);

    }

    public function listarExtratoCartaoCliente($clienteId)
    {
        return ExtratoCartaoClienteDatabase::with(['cliente'])
            ->where('clienteId', $clienteId)->paginate();
    }
}
