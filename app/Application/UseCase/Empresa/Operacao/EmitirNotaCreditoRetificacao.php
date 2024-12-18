<?php

namespace App\Application\UseCase\Empresa\Operacao;

use App\Application\UseCase\Empresa\Faturas\GetAnoDeFaturacao;
use App\Application\UseCase\Empresa\Faturas\GetNumeroSerieDocumento;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Http\Controllers\empresa\Operacao\CreateNotaCreditoRetificacao;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Infra\Repository\Empresa\NotaCreditoRepository;
use App\Repositories\Empresa\TraitChavesEmpresa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpseclib\Crypt\RSA;

class EmitirNotaCreditoRetificacao
{

    use CreateNotaCreditoRetificacao;
    use TraitChavesEmpresa;
    use TraitChavesEmpresa;

    private NotaCreditoRepository $notaCreditoRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->notaCreditoRepository = $repositoryFactory->createNotaCreditoRepository();
    }


    public function execute(Request $request)
    {
        $input = (object)$request;

        $getAnoFaturacao = new GetAnoDeFaturacao(new DatabaseRepositoryFactory());
        $getYearNow = $getAnoFaturacao->execute();
        $yearNow = Carbon::parse(Carbon::now())->format('Y');
        if ($getYearNow) {
            $yearNow = $getYearNow->valor;
        }
        $getNumeroSerieDocumento = new GetNumeroSerieDocumento(new DatabaseRepositoryFactory());
        $numeroSerieDocumento = $getNumeroSerieDocumento->execute();
        if ($numeroSerieDocumento) {
            $numeroSerieDocumento = $numeroSerieDocumento->valor;
        } else {
            $numeroSerieDocumento = "ATO";
        }
        $ultimoDoc = $this->notaCreditoRepository->lastDocument();
        $numSequencia = 1;
        $hashAnterior = "";
        if ($ultimoDoc) {
            $numSequencia = ++$ultimoDoc->numSequencia;
            $hashAnterior = $ultimoDoc->hashValor;
        }
        $numDoc = 'NC ' . $numeroSerieDocumento . $yearNow . '/' . $numSequencia;
        $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        $rsa = new RSA(); //Algoritimo RSA

        $privatekey = $this->pegarChavePrivada();
        $rsa->loadKey($privatekey);
        $plaintext = str_replace(date(' H:i:s'), '', $datactual) . ';' . str_replace(' ', 'T', $datactual) . ';' . $numDoc . ';' . number_format($input->total, 2, ".", "") . ';' . $hashAnterior;

        // HASH
        $hash = 'sha1'; // Tipo de Hash
        $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
        $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
        $hashValor = base64_encode($signaturePlaintext);

       //dd($input);

        $input['plaintext'] = $plaintext;
        $input['texto_hash'] = $plaintext;
        $input['numSequencia'] = $numSequencia;
        $input['numDoc'] = $numDoc;
        $input['hashValor'] = $hashValor;
        $input['created_at'] = $datactual;
        $input['updated_at'] = $datactual;

        /**
        $data['tipoOperacao'] = $input->tipoOperacao;
        $data['isencaoCargaTransito'] = $input->isencaoCargaTransito;
        $data['codigo_moeda'] = $input->moeda;
        $data['observacao'] = $input->observacao;
        $data['faturaId'] = $input->facturaId;
        $data['facturaId'] = $input->facturaId;
        $data['nomeCliente'] = $input->nomeCliente;
        $data['telefone_cliente'] = $input->telefoneCliente;
        $data['nif_cliente'] = $input->nifCliente;
        $data['email_cliente'] = $input->emailCliente;
        $data['endereco_cliente'] = $input->enderecoCliente;
        $data['tipo documento'] = $input->tipoDocumento;
        $data['tipoDocumento'] = $input->tipoDocumento;
        $data['codigo_moeda'] = 1;
        $data['numSequencia'] = $numSequencia;
        $data['numDoc'] = $numDoc;
        $data['hashValor'] = $hashValor;
        $data['clienteId'] = $input->clienteId;
        $data['cliente_id'] = $input->clienteId;
        $data['empresa_id'] = auth()->user()->empresa_id;
        $data['centroCustoId'] = session()->get('centroCustoId');
        $data['user_id'] = auth()->user()->id;
        $data['operador'] = auth()->user()->name;
        $data['taxaIva'] = $input->taxaIva;
        $data['cambioDia'] = $input->cambioDia;
        $data['moeda'] = $input->moeda;
        $data['moedaPagamento'] = $input->moedaPagamento;
        $data['contraValor'] = $input->contraValor;
        $data['valorConsumo'] = $input->valorConsumo;
        $data['valorIliquido'] = $input->valorIliquido;
        $data['valorliquido'] = $input->valorliquido;
        $data['valorImposto'] = $input->valorImposto;
        $data['taxaImpostoPredial'] = $input->taxaImpostoPredial;
        $data['valorImpostoPredial'] = $input->valorImpostoPredial;
        $data['totalDesconto'] = $input->totalDesconto;
        $data['total'] = $input->total;
        $data['formaPagamentoId'] = $input->formaPagamentoId;
        $data['isencaoIVA'] = $input->isencaoIVA;
        $data['isencaoOcupacao'] = $input->isencaoOcupacao;
        $data['valorRetencao'] = $input->valorRetencao;
        $data['taxaRetencao'] = $input->valorRetencao > 0 ? 6.5 : 0;
        $data['tipoFatura'] = $input->tipoFatura;
        $data['tipoMercadoria'] = $input->tipoMercadoria;
        $data['items'] = $input->items;
         * */

        //return $this->createNotaCredito($data, false);
        if ($request->tipoFatura == 1) { //Serviço de carga
            return $this->createNotaCreditoCarga($input);
        } else if ($request->tipoFatura == 2) { //Serviço aeroportuario
            return $this->createNotaCreditoAeroportuario($input);
        }else if($request->tipoFatura == 3){
            return $this->createNotaCreditoOutroServico($input);
        }else if($request->tipoFatura == 4){
            return $this->createNotaCreditoServicoComercial($input);
        }else {
            throw new \Error("Não implementado");
        }
    }

}
