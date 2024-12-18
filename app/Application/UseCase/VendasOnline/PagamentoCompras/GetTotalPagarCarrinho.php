<?php

namespace App\Application\UseCase\VendasOnline\PagamentoCompras;

use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\CarrinhoVendasOnlineRepository;
use App\Infra\Repository\VendasOnline\PagamentoVendasOnlineRepository;
use Illuminate\Support\Facades\Auth;

class GetTotalPagarCarrinho
{
    private CarrinhoVendasOnlineRepository $carrinhoVendasOnlineRepository;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->carrinhoVendasOnlineRepository = $repositoryFactory->createCarrinhoVendaOnlineRepository();
    }
    public function execute(){
        $userId = Auth::user()->id;
        $carrinhos = $this->carrinhoVendasOnlineRepository->getCarrinhos($userId);

        $total = 0;
        foreach ($carrinhos as $carrinho){
            $total += $carrinho['produto']['preco_venda'] * $carrinho['quantidade'];
        }
        return $total;
    }
}
