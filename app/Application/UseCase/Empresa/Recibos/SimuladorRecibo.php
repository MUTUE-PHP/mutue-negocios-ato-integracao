<?php

namespace App\Application\UseCase\Empresa\Recibos;

use App\Domain\Entity\Empresa\Recibos\Recibo;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ReciboRepository;

class SimuladorRecibo
{

    private ReciboRepository $reciboRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->reciboRepository = $repositoryFactory->createReciboRepository();
    }
    public function execute($data){
        $recibo = new Recibo(
            $data['clienteId'],
            $data['nomeCliente'],
            $data['nifCliente'],
            $data['telefoneCliente'],
            $data['emailCliente'],
            $data['enderecoCliente'],
            $data['anulado'],
            $data['totalEntregue'],
            $data['totalImposto'],
            $data['facturaId'],
            $data['totalFatura'],
            $data['formaPagamentoId'],
            $data['numeroOperacaoBancaria'],
            $data['dataOperacao'],
            $data['comprovativoBancario'],
            $data['observacao'],
            $data['numSequenciaRecibo'],
        );

        return $recibo;
    }
}