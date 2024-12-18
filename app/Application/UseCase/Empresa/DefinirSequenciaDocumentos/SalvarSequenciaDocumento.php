<?php

namespace App\Application\UseCase\Empresa\DefinirSequenciaDocumentos;

use App\Domain\Entity\Empresa\SequenciaDocumento;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Infra\Repository\Empresa\SequenciaDocumentoRepository;
use Illuminate\Support\Facades\Validator;


class SalvarSequenciaDocumento
{
    private SequenciaDocumentoRepository $sequenciaDocumentoRepository;
    private FaturaRepository $faturaRepository;

    public function __construct(RepositoryFactory $repositoryFactory){
        $this->sequenciaDocumentoRepository = $repositoryFactory->createSequenciaDocumentoRepository();
        $this->faturaRepository = $repositoryFactory->createFaturaRepository();
    }
    public function execute($request){
        $message = [
            'sequencia.required' => 'Informe o número da sequência'
        ];
        $validator = Validator::make($request->all(), [
            'sequencia' => ['required'],
        ], $message);

        $sequenciaDocumento = new SequenciaDocumento(
            $request->sequencia,
            $request->tipo_documento,
            $request->tipoDocumentoNome,
            $request->serie_documento
        );
        $tipoDocumentoSequenciaFatory = new TipoDocumentoSequenciaFatory();
        $repositoryFatory = $tipoDocumentoSequenciaFatory->execute($request->tipo_documento);
        $isSequencia = $repositoryFatory->existeSequencia($request->sequencia);

        $sequenciaMenorExistentes = $repositoryFatory->sequenciaMenorExistentes($request->sequencia);

        if($sequenciaMenorExistentes){
            throw new \Exception("Está sequência é menor que as existentes");
        }
        if($isSequencia){
            throw new \Exception("Sequência já cadastrado");
        }
        if ($validator->fails()) {
            throw  new \Exception($validator->errors()->messages()['sequencia'][0]);
        }
        if(false){ //verificar aqui se numero de sequencia existe nas faturas/recibos
            throw new \Exception("Número da sequência já cadastrado");
        }
        $output = $this->sequenciaDocumentoRepository->salvar($sequenciaDocumento);
        return response()->json($output, 200);
    }

}
