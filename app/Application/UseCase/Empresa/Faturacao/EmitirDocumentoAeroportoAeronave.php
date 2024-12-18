<?php

namespace App\Application\UseCase\Empresa\Faturacao;

use App\Application\UseCase\Empresa\Faturas\GetAnoDeFaturacao;
use App\Application\UseCase\Empresa\Faturas\GetNumeroSerieDocumento;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaCarga;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Repositories\Empresa\TraitChavesEmpresa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpseclib\Crypt\RSA;
use phpseclib\Crypt\RSA as Crypt_RSA;
use NumberFormatter;

class EmitirDocumentoAeroportoAeronave
{
    use TraitChavesEmpresa;

    private FaturaRepository $faturaRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->faturaRepository = $repositoryFactory->createFaturaRepository();
    }

    public function execute(Request $request)
    {
        // dd($request->all());
        $ultimaFatura = $this->faturaRepository->pegarUltimaFactura($request->tipoDocumento);

        $hashAnterior = "";
        if ($ultimaFatura) {
            $data_factura = Carbon::createFromFormat('Y-m-d H:i:s', $ultimaFatura->created_at);
            $hashAnterior = $ultimaFatura->hashValor;
        } else {
            $data_factura = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        }
        //ManipulaÃ§Ã£o de datas: data da factura e data actual
        //$data_factura = Carbon::createFromFormat('Y-m-d H:i:s', $facturas->created_at);
        $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        /*Recupera a sequÃªncia numÃ©rica da Ãºltima factura cadastrada no banco de dados e adiona sempre 1 na sequÃªncia caso o ano da afctura seja igual ao ano actual;
        E reinicia a sequÃªncia numÃ©rica caso se constate que o ano da factura Ã© inferior ao ano actual.*/
        if ($data_factura->diffInYears($datactual) == 0) {
            if ($ultimaFatura) {
                $data_factura = Carbon::createFromFormat('Y-m-d H:i:s', $ultimaFatura->created_at);
                $numSequenciaFactura = intval($ultimaFatura->numSequenciaFactura) + 1;
            } else {
                $data_factura = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                $numSequenciaFactura = 1;
            }
        } else if ($data_factura->diffInYears($datactual) > 0) {
            $numSequenciaFactura = 1;
        }

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

        if ($request->tipoDocumento == 1) {
            $doc = "FR ";
        } else if ($request->tipoDocumento == 2) {
            $doc = "FT ";
        } else {
            $doc = "PP ";
        }

        $numeracaoFactura = $doc . $numeroSerieDocumento . $yearNow . '/' . $numSequenciaFactura; //retirar somente 3 primeiros caracteres na facturaSerie da factura: substr('abcdef', 0, 3);

        $statusFatura = 2;//Pago
        $dataVencimento = null;

        $rsa = new RSA(); //Algoritimo RSA

        $privatekey = $this->pegarChavePrivada();
        $publickey = $this->pegarChavePublica();

        // Lendo a private key
        $rsa->loadKey($privatekey);

        $plaintext = str_replace(date(' H:i:s'), '', $datactual) . ';' . str_replace(' ', 'T', $datactual) . ';' . $numeracaoFactura . ';' . number_format(($request->valorliquido + $request->valorImposto), 2, ".", "") . ';' . $hashAnterior;

        // HASH
        $hash = 'sha1'; // Tipo de Hash
        $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

        //ASSINATURA
        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
        $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
        $hashValor = base64_encode($signaturePlaintext);


        $faturaId = DB::table('facturas')->insertGetId([
            'texto_hash' => $plaintext,
            'tipo_documento' => $request->tipoDocumento,
            'formaPagamentoId' => $request->formaPagamentoId,
            'observacao' => $request->observacao,
            'isencaoIVA' => $request->isencaoIVA ? 'Y' : 'N',
            'taxaRetencao' => $request->taxaRetencao,
            'valorRetencao' => $request->valorRetencao,
            'numSequenciaFactura' => $numSequenciaFactura,
            'numeracaoFactura' => str_replace("PP", "FP", $numeracaoFactura),
            'hashValor' => $hashValor,
            'empresa_id' => auth()->user()->empresa_id,
            'nifEmpresa' => auth()->user()->empresa->nif,
            'nomeEmpresa' => auth()->user()->empresa->nome,
            'centroCustoId' => session()->get('centroCustoId'),
            'user_id' => auth()->user()->id,
            'operador' => auth()->user()->name,
            'clienteId' => $request->clienteId,
            'nome_do_cliente' => $request->nomeCliente,
            'nomeProprietario' => $request->nomeProprietario,
            'telefone_cliente' => $request->telefoneCliente,
            'nif_cliente' => $request->nifCliente,
            'email_cliente' => $request->emailCliente,
            'endereco_cliente' => $request->enderecoCliente,
            'tipoDeAeronave' => $request->tipoDeAeronave,
            'numeroAeronaveChegada' => $request->numeroAeronaveChegada,
            'numeroAeronavePartida' => $request->numeroAeronavePartida,
            'horaMangaAcoplagem' => $request->horaMangaAcoplagem,
            'horaMangaDesacoplagem' => $request->horaMangaDesacoplagem,
            'pesoMaximoDescolagem' => $request->pesoMaximoDescolagem,
            'dataDeAterragem' => $request->dataDeAterragem,
            'dataDeDescolagem' => $request->dataDeDescolagem,
            'horaDeAterragem' => $request->horaDeAterragem,
            'horaDeDescolagem' => $request->horaDeDescolagem,
            'peso' => $request->pesoTotal,
            'horaExtra' => $request->horaExtra,
            'tipoDocumento' => $request->tipoDocumento,
            'taxaIva' => $request->taxaIva,
            'cambioDia' => $request->cambioDia,
            'contraValor' => $request->contraValor,
            'valorliquido' => $request->valorliquido,
            'totalDesconto' => $request->valorDesconto,
            'valorIliquido' => $request->valorIliquido,
            'valorImposto' => $request->valorImposto,
            'tipoFatura' => 2,
            'total' => $request->total,
            'moeda' => $request->moeda,
            'moedaPagamento' => $request->moedaPagamento,
            'created_at' => $datactual,
            'updated_at' => $datactual
        ]);
        //Gerar o codigo de barra
        DB::table('facturas')->where('id', $faturaId)->update([
            'codigoBarra' => $this->getCodigoBarra($faturaId, $request->clienteId)
        ]);
        $TAXA_IVA_ZERO = 1;
        $TAXA_IVA_14 = 2;
        foreach ($request->items as $item) {
            $item = (object)$item;
            $peso = null;
            if ($item->produtoId == 7 || $item->produtoId == 12 || $item->produtoId == 13) {
                $peso = $item->peso;
            }
            $codigoTaxa = $request->isencaoIVA ? $TAXA_IVA_ZERO : ($item->taxaIva > 0 ? $TAXA_IVA_14 : $TAXA_IVA_ZERO);
            DB::table('factura_items')->insert([
                'produtoId' => $item->produtoId,
                'quantidade' => $item->quantidade ?? null,
                'nomeProduto' => $item->nomeProduto,
                'precoVendaProduto' => $item->precoVendaProduto,
                'horaEstacionamento' => $item->horaEstacionamento,
                'taxa' => $item->taxa,
                'taxaLuminosa' => $item->taxaLuminosa,
                'taxaAduaneiro' => $item->taxaAduaneiro,
                'sujeitoDespachoId' => $item->sujeitoDespachoId,
                'peso' => $peso ?? null,
                'horaExtra' => $item->horaExtra,
                'taxaAbertoAeroporto' => $item->taxaAbertoAeroporto,
                'valorImposto' => $item->valorImposto,
                'total' => $item->total,
                'totalIva' => $item->totalIva,
                'horaAberturaAeroporto' => $item->horaAberturaAeroporto,
                'horaFechoAeroporto' => $item->horaFechoAeroporto,
                'desconto' => $item->desconto,
                'valorDesconto' => $item->valorDesconto,
                'codigoTaxaIvaId' => $codigoTaxa,
                'taxaIva' => $item->taxaIva,
                'valorIva' => $item->valorIva,
                'factura_id' => $faturaId
            ]);
        }
        return $faturaId;
    }

    public function getCodigoBarra($faturaId, $clienteId)
    {
        return "1000" . $clienteId . "" . $faturaId . "" . auth()->user()->id;
    }

}
