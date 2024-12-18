<?php

namespace App\Infra\Repository\Empresa;
use App\Application\UseCase\Empresa\Faturas\GetAnoDeFaturacao;
use App\Application\UseCase\Empresa\Faturas\GetNumeroSerieDocumento;
use App\Domain\Entity\Empresa\Operacao\AnulacaoDocumento;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Factura;
use App\Models\empresa\NotaCredito;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotaCreditoRepository
{
    public function salvar(AnulacaoDocumento $anulacaoDocumento)
    {
        return NotaCredito::create([
            'uuid' => Str::uuid(),
            'facturaId' => $anulacaoDocumento->getFacturaId(),
            'reciboId' => $anulacaoDocumento->getReciboId(),
            'numDoc' => $anulacaoDocumento->getNumDoc(),
            'hash' => $anulacaoDocumento->getHash(),
            'hashTexto' => $anulacaoDocumento->getHashTexto(),
            'numSequencia' => $anulacaoDocumento->getNumSequencia(),
            'userId' => auth()->user()->id,
            'empresaId' => auth()->user()->empresa_id,
            'descricao' => $anulacaoDocumento->getDescricao(),
        ]);
    }
    public function lastDocument(){

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
          FROM notas_creditos
          WHERE empresa_id = " . auth()->user()->empresa_id . " and  SUBSTRING_INDEX(numDoc, '/', 1) LIKE '%" . $yearNow . "%' and numDoc  LIKE '%" . $numeroSerieDocumento . "%'
          ORDER BY id DESC
          LIMIT 1");
        if (!$resultados) return null;
        return json_decode(json_encode($resultados[0]));
    }

}
