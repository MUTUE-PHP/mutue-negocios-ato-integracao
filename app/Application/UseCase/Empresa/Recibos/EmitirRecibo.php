<?php

namespace App\Application\UseCase\Empresa\Recibos;

use App\Application\UseCase\Empresa\Faturas\GetAnoDeFaturacao;
use App\Application\UseCase\Empresa\Faturas\GetNumeroSerieDocumento;
use App\Domain\Entity\Empresa\Recibos\Recibo;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\ReciboRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use illuminate\Support\Str;
use Livewire\WithFileUploads;

class EmitirRecibo
{

    use WithFileUploads;

    private ReciboRepository $reciboRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->reciboRepository = $repositoryFactory->createReciboRepository();
    }
    public function execute($data){


        if($data['comprovativoBancario']){
            $fileName= Str::slug($data['numeroOperacaoBancaria']) . "." .$data['comprovativoBancario']->getClientOriginalExtension();
            $path= $data['comprovativoBancario']->storeAs('comprovativos', $fileName, 'public');
            $data['comprovativoBancario'] = $path;

        }

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

        $ultimoDoc = $this->reciboRepository->lastDocument();

        $numSequenciaRecibo = 1;
        if ($ultimoDoc) {
            $numSequenciaRecibo = ++$ultimoDoc->numSequenciaRecibo;
        }
        $numeracaoRecibo = 'RC '.$numeroSerieDocumento . $yearNow . '/' . $numSequenciaRecibo;

        $data = [
            'clienteId' => $data['clienteId'],
            'nomeCliente' => $data['nomeCliente'],
            'nifCliente' => $data['nifCliente'],
            'telefoneCliente' => $data['telefoneCliente'],
            'emailCliente' => $data['emailCliente'],
            'enderecoCliente' => $data['enderecoCliente'],
            'anulado' => $data['anulado'],
            'totalEntregue' => $data['totalEntregue'],
            'totalImposto' => $data['totalImposto'],
            'facturaId' => $data['facturaId'],
            'totalFatura' => $data['totalFatura'],
            'totalDebitar' => ($data['totalDebitar'] - $data['totalEntregue']),
            'formaPagamentoId' => $data['formaPagamentoId'],
            'numeroOperacaoBancaria' => $data['numeroOperacaoBancaria'],
            'dataOperacao' => $data['dataOperacao'],
            'comprovativoBancario' => $data['comprovativoBancario'],
            'observacao' => $data['observacao'],
            'numSequenciaRecibo' => $numSequenciaRecibo,
            'numeracaoRecibo' => $numeracaoRecibo
        ];
        return $this->reciboRepository->emitirRecibo($data);
    }
}
