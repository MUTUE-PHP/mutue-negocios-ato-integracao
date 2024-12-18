<?php

namespace App\Application\UseCase\Empresa\Operacao;

use App\Application\UseCase\Empresa\Faturas\GetAnoDeFaturacao;
use App\Application\UseCase\Empresa\Faturas\GetNumeroSerieDocumento;
use App\Domain\Entity\Empresa\Operacao\AnulacaoDocumento;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Http\Controllers\empresa\Operacao\CreateNotaCreditoRetificacao;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\NotaCreditoRepository;
use App\Repositories\Empresa\TraitChavesEmpresa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpseclib\Crypt\RSA;
use phpseclib\Crypt\RSA as Crypt_RSA;

class EmitirAnulacaoFatura
{
    use TraitChavesEmpresa;
    use CreateNotaCreditoRetificacao;


    private NotaCreditoRepository $notaCreditoRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->notaCreditoRepository = $repositoryFactory->createNotaCreditoRepository();
    }

    public function execute($input)
    {
        $input = (object)$input;
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

        $rsa = new Crypt_RSA(); //Algoritimo RSA

        $privatekey = $this->pegarChavePrivada();
        $rsa->loadKey($privatekey);
        $plaintext = str_replace(date(' H:i:s'), '', $datactual) . ';' . str_replace(' ', 'T', $datactual) . ';' . $numDoc . ';' . number_format($input->total, 2, ".", "") . ';' . $hashAnterior;

        // HASH
        $hash = 'sha1'; // Tipo de Hash
        $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
        $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
        $hashValor = base64_encode($signaturePlaintext);

        $data['plaintext'] = $plaintext;
        $data['texto_hash'] = $plaintext;
        $data['codigo_moeda'] = $input->codigo_moeda;
        $data['observacao'] = $input->observacao;
        $data['faturaId'] = $input->id;
        $data['facturaId'] = $input->id;
        $data['nome_do_cliente'] = $input->nome_do_cliente;
        $data['nomeProprietario'] = $input->nomeProprietario;
        $data['telefone_cliente'] = $input->telefone_cliente;
        $data['nif_cliente'] = $input->nif_cliente;
        $data['email_cliente'] = $input->email_cliente;
        $data['endereco_cliente'] = $input->endereco_cliente;
        $data['tipo_documento'] = $input->tipo_documento;
        $data['numSequencia'] = $numSequencia;
        $data['numDoc'] = $numDoc;
        $data['hashValor'] = $hashValor;
        $data['clienteId'] = $input->clienteId;
        $data['cliente_id'] = $input->clienteId;
        $data['empresa_id'] = $input->empresa_id;
        $data['centroCustoId'] = session()->get('centroCustoId');
        $data['user_id'] = auth()->user()->id;
        $data['operador'] = auth()->user()->name;
        $data['paisOrigemId'] = $input->paisOrigemId;
        $data['cartaDePorte'] = $input->cartaDePorte;
        $data['tipoDeAeronave'] = $input->tipoDeAeronave;
        $data['pesoMaximoDescolagem'] = $input->pesoMaximoDescolagem;
        $data['dataDeAterragem'] = $input->dataDeAterragem;
        $data['dataDeDescolagem'] = $input->dataDeDescolagem;
        $data['horaDeAterragem'] = $input->horaDeAterragem;
        $data['horaDeDescolagem'] = $input->horaDeDescolagem;
        $data['horaEstacionamento'] = $input->horaEstacionamento;
        $data['peso'] = $input->peso;
        $data['dataEntrada'] = $input->dataEntrada;
        $data['dataSaida'] = $input->dataSaida;
        $data['nDias'] = $input->nDias;
        $data['taxaIva'] = $input->taxaIva;
        $data['cambioDia'] = $input->cambioDia;
        $data['moeda'] = $input->moeda;
        $data['moedaPagamento'] = $input->moedaPagamento;
        $data['horaExtra'] = $input->horaExtra;
        $data['contraValor'] = $input->contraValor;
        $data['valorConsumo'] = $input->valorConsumo;
        $data['valorIliquido'] = $input->valorIliquido;
        $data['valorliquido'] = $input->valorliquido;
        $data['valorImposto'] = $input->valorImposto;
        $data['taxaImpostoPredial'] = $input->taxaImpostoPredial;
        $data['valorImpostoPredial'] = $input->valorImpostoPredial;
        $data['totalDesconto'] = $input->totalDesconto;
        $data['total'] = $input->total;
        $data['codigoBarra'] = $input->codigoBarra;
        $data['tipoDocumento'] = $input->tipoDocumento;
        $data['formaPagamentoId'] = $input->formaPagamentoId;
        $data['tipoOperacao'] = $input->tipoOperacao;
        $data['isencaoIVA'] = $input->isencaoIVA;
        $data['isencaoOcupacao'] = $input->isencaoOcupacao;
        $data['isencao24hCargaTransito'] = $input->isencao24hCargaTransito;
        $data['convertido'] = $input->convertido;
        $data['anulado'] = $input->anulado;
        $data['taxaRetencao'] = $input->taxaRetencao;
        $data['valorRetencao'] = $input->valorRetencao;
        $data['tipoFatura'] = $input->tipoFatura;
        $data['tipoMercadoria'] = $input->tipoMercadoria;
        $data['created_at'] = $datactual;
        $data['updated_at'] = $datactual;
        $data['items'] = $input->facturas_items;
        return $this->createNotaCredito($data, true);


        /**
        $data['nomeCliente'] = $input->nome_do_cliente;
        $data['telefoneCliente'] = $input->telefone_cliente;
        $data['nifCliente'] = $input->nif_cliente;
        $data['emailCliente'] = $input->email_cliente;
        $data['enderecoCliente'] = $input->endereco_cliente;
        $data['facturaId'] = $input->faturaId;
        $data['enderecoCliente'] = $input->endereco_cliente;
        $data['isencaoIVA'] = $input->isencaoIVA == "N" ? false : true;
         */

        //if ($data->tipoFatura == 1) { //Serviço de carga
          //  return $this->createNotaCredito($data, true);
       // } else if ($data->tipoFatura == 2) { //Serviço aeroportuario
        //    return $this->createNotaCreditoAeroportuario($data, true);
        //}else if($data->tipoFatura == 4){
      //      return $this->createNotaCreditoCarga($data, true);
       // }else {
        //    throw new \Error("Não implementado");
      //  }
    }

}
