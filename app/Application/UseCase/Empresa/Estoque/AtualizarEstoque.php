<?php

namespace App\Application\UseCase\Empresa\Estoque;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\AtualizarEstoqueRepository;
use App\Infra\Repository\Empresa\ExistenciaStockRepository;
use Illuminate\Http\Request;
use App\Domain\Entity\Empresa\Estoque\AtualizarEstoque as AtualizarStock;

class AtualizarEstoque
{
    private AtualizarEstoqueRepository $atualizarEstoqueRepository;
    private ExistenciaStockRepository $existenciaStockRepository;

    public function __construct(RepositoryFactory $repositoryFactory){
        $this->atualizarEstoqueRepository = $repositoryFactory->createAtualizarEstoqueRepository();
        $this->existenciaStockRepository = $repositoryFactory->createExistenciaStockRepository();
    }
    public function execute(Request $request){
        $atualizarEstoque = new AtualizarStock(
            $request->produtoId,
            $request->quantidadeAnterior,
            $request->quantidadeNova,
            $request->armazemId,
            $request->descricao,
            $request->centroCustoId
        );
        $outputAtualizarEstoque =  $this->atualizarEstoqueRepository->atualizarEstoque($atualizarEstoque);
        if(!$outputAtualizarEstoque) throw  new \Error("Erro ao atualizar o estoque");
        $this->existenciaStockRepository->atualizarExistenciaStock(
            $request->produtoId,
            $request->armazemId,
            $request->quantidadeNova
        );
        return $outputAtualizarEstoque;
    }

}
