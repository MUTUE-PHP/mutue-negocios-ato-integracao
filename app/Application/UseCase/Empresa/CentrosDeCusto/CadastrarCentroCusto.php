<?php

namespace App\Application\UseCase\Empresa\CentrosDeCusto;

use App\Domain\Entity\Empresa\CentrosDeCusto\CentroCusto;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\CentroCustoRepository;
use Illuminate\Http\Request;

class CadastrarCentroCusto
{
    use TraitUploadFileDocEmpresa;
    use TraitUploadFileLogotipoEmpresa;
    private CentroCustoRepository $centroCustoRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->centroCustoRepository = $repositoryFactory->createCentroCustoRepository();
    }
    public function execute(Request $request)
    {
        $request = (object)$request;

        $urlFileNif = null;
        $urlFileAlvara = null;

        if(!is_string($request->logotipo)){
            $urlLogotipo = $this->uploadFileLogotipo($request->logotipo);
        }else{
            $urlLogotipo = $request->logotipo;
        }
        if ($request->fileAlvara) {
            if(!is_string($request->fileAlvara)){
                $urlFileAlvara = $this->uploadFile($request->fileAlvara);
            }else{
                $urlFileAlvara = $request->fileAlvara;
            }
        }
        if ($request->fileNif) {
            if(!is_string($request->fileNif)){
                $urlFileNif =  $this->uploadFile($request->fileNif);
            }else{
                $urlFileNif = $request->fileNif;
            }
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
            $request->statusId
        );
        return $this->centroCustoRepository->salvar($centroCusto);
    }

}
