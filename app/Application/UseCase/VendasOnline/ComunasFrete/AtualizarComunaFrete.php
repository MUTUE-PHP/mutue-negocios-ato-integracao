<?php

namespace App\Application\UseCase\VendasOnline\ComunasFrete;
use App\Domain\Entity\VendasOnline\ComunaFrete;
use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Infra\Repository\VendasOnline\ComunasFreteRepository;

class AtualizarComunaFrete
{
    private ComunasFreteRepository $comunasFreteRepository;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->comunasFreteRepository = $repositoryFactory->createComunasFreteRepository();
    }
    public function execute($request, $comunaId){
        $comunaFrete = new ComunaFrete(
            $request->designacao,
            $request->valorEntrega,
            $request->municipioId,
            $request->statusId
        );
        return $this->comunasFreteRepository->atualizar($comunaFrete , $comunaId);
    }
}
