<?php

namespace App\Application\UseCase\VendasOnline\SubscricaoVendaOnline;

use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\SubscricaoVendaOnlineRepository;

class DesativarSubscricaoVendaOnline
{
    private SubscricaoVendaOnlineRepository $subscricaoVendaOnlineRepository;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->subscricaoVendaOnlineRepository = $repositoryFactory->createSubscricaoVendaOnlineRepository();
    }
    public function execute($email){
        return $this->subscricaoVendaOnlineRepository->delete($email);
    }
}
