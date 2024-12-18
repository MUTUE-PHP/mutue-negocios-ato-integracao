<?php

namespace App\Application\UseCase\VendasOnline\PerguntasFrequentes;

use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\PerguntaFrequenteRepository;

class CadastrarPerguntasFrequentes
{
    private PerguntaFrequenteRepository $perguntaFrequenteRepository;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->perguntaFrequenteRepository = $repositoryFactory->createPerguntaFrequenteRepository();
    }
    public function execute($request){
        return $this->perguntaFrequenteRepository->salvar($request);
    }

}
