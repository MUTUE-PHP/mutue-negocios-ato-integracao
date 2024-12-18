<?php

namespace App\Infra\Repository\Empresa;

use App\Application\UseCase\Empresa\Faturas\GetAnoDeFaturacao;
use App\Application\UseCase\Empresa\Faturas\GetNumeroSerieDocumento;
use App\Domain\Entity\Empresa\Recibos\Recibo;
use App\Domain\Entity\Empresa\SequenciaDocumento;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Recibos as ReciboDatabase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReciboRepository
{
    public function existeEstaSequencia(SequenciaDocumento $sequenciaDocumento)
    {
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        return ReciboDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('created_at', 'like', '%' . $yearNow . '%')
            ->where('numeracao_recibo', 'like', '%' . $sequenciaDocumento->getSerieDocumento() . '%')
            ->where('numSequenciaRecibo', $sequenciaDocumento->getSequencia())
            ->first();
    }

    public function sequenciaMenorExistentes(SequenciaDocumento $sequenciaDocumento)
    {
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        return ReciboDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('created_at', 'like', '%' . $yearNow . '%')
            ->where('numeracao_recibo', 'like', '%' . $sequenciaDocumento->getSerieDocumento() . '%')
            ->where('numSequenciaRecibo', '>', $sequenciaDocumento->getSequencia())
            ->first();
    }

    public function lastDocument()
    {
        $getAnoFaturacao = new GetAnoDeFaturacao(new DatabaseRepositoryFactory());
        $getYearNow = $getAnoFaturacao->execute();
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        if ($getYearNow) {
            $yearNow = $getYearNow->valor;
        }
        $getNumeroSerieDocumento = new GetNumeroSerieDocumento(new DatabaseRepositoryFactory());
        $numeroSerieDocumento = $getNumeroSerieDocumento->execute();
        if($numeroSerieDocumento){
            $numeroSerieDocumento = $numeroSerieDocumento->valor;
        }else{
            $numeroSerieDocumento = "ATO";
        }

        $resultados = DB::connection('mysql2')->select("SELECT *
          FROM recibos
          WHERE empresaId = " . auth()->user()->empresa_id . " and  SUBSTRING_INDEX(numeracaoRecibo, '/', 1) LIKE '%" . $yearNow . "%' and numeracaoRecibo  LIKE '%" . $numeroSerieDocumento . "%'
          ORDER BY id DESC
          LIMIT 1");

        if (!$resultados) return null;
        return json_decode(json_encode($resultados[0]));
    }

    public function getTotalDebitadoFatura($faturaId)
    {
        return ReciboDatabase::where('empresaId', auth()->user()->empresa_id)
            ->where('facturaId', $faturaId)->sum('totalEntregue');
    }

    public function emitirRecibo($data)
    {
        return ReciboDatabase::create([
            'numeracaoRecibo' => $data['numeracaoRecibo'],
            'clienteId' => $data['clienteId'],
            'anulado' => 'N',
            'totalEntregue' => $data['totalEntregue'],
            'userId' => auth()->user()->id,
            'empresaId' => auth()->user()->empresa_id,
            'nifEmpresa' => auth()->user()->empresa->nif,
            'nomeEmpresa' => auth()->user()->empresa->nome,
            'facturaId' => $data['facturaId'],
            'totalFatura' => $data['totalFatura'],
            'totalImposto' => $data['totalImposto'],
            'totalDebitar' => $data['totalDebitar'],
            'formaPagamentoId' => $data['formaPagamentoId'],
            'numeroOperacaoBancaria' => $data['numeroOperacaoBancaria'],
            'dataOperacao' => $data['dataOperacao'],
            'comprovativoBancario' => $data['comprovativoBancario'],
            'observacao' => $data['observacao'],
            'numSequenciaRecibo' => $data['numSequenciaRecibo'],
            'nomeCliente' => $data['nomeCliente'],
            'telefoneCliente' => $data['telefoneCliente'],
            'nifCliente' => $data['nifCliente'],
            'emailCliente' => $data['emailCliente'],
            'enderecoCliente' => $data['enderecoCliente']
        ]);
    }
}
