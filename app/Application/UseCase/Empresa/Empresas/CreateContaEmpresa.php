<?php

namespace App\Application\UseCase\Empresa\Empresas;

use App\Domain\Entity\Empresa\Empresa;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\EmpresaRepository;

class CreateContaEmpresa
{
    private EmpresaRepository $empresaRepository;
    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->empresaRepository = $repositoryFactory->createEmpresaRepository();
    }

    public function execute($input){

        $empresa = new Empresa(
            $input->nome,
            $input->telefone1,
            $input->telefone2,
            $input->telefone3,
            $input->pessoaDeContato,
            $input->endereco,
            $input->website,
            $input->regimeId,
            $input->nif,
            $input->email,
            $input->logotipo,
            $input->paisId,
            $input->tipoClienteId,
            $input->canalId,
            $input->statusId,
            $input->provinciaId,
            $input->vendaOnline,
            $input->fileAlvara,
            $input->fileNif,
            $input->referencia
        );
       return $this->empresaRepository->salvar($empresa);

    }

}
