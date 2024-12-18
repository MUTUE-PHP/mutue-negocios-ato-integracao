<?php

namespace App\Application\UseCase\Empresa\CentrosDeCusto;

use App\Domain\Entity\Empresa\CentrosDeCusto\CentroCusto;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CentroCustoRepository;
use Illuminate\Http\Request;

class AtualizarCentroCusto
{

    use TraitUploadFileDocEmpresa;
    use TraitUploadFileLogotipoEmpresa;

    private CentroCustoRepository $centroCustoRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->centroCustoRepository = $repositoryFactory->createCentroCustoRepository();
    }

    public function execute(Request $request, $centroCustoId)
    {
        $request = (object)$request;

        $urlFileNif = $request->antFileNif;
        $urlFileAlvara = $request->antFileAlvara;
        $urlLogotipo = $request->antLogotipo;

        if($request->logotipo){
            $urlLogotipo = $this->uploadFileLogotipo($request->logotipo, $urlLogotipo);
        }
        if ($request->fileAlvara) {
            $urlFileAlvara = $this->uploadFile($request->fileAlvara, $urlFileAlvara);
        }
        if ($request->fileNif) {
            $urlFileNif = $this->uploadFile($request->fileNif, $urlFileNif);
        }
        $centroCusto = new CentroCusto(
            $request->nome,
            $request->endereco,
            $request->nif,
            $request->cidade,
            $urlLogotipo,
            $request->email,
            $request->website,
            $request->telefone,
            $request->pessoaContato,
            $urlFileAlvara,
            $urlFileNif,
            $request->statusId,
        );

        return $this->centroCustoRepository->update($centroCusto, $centroCustoId);
    }
}
