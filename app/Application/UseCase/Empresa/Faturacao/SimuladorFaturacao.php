<?php

namespace App\Application\UseCase\Empresa\Faturacao;
use App\Domain\Entity\Empresa\Faturacao\Fatura;
use App\Domain\Entity\Empresa\Faturacao\FaturaItem;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Infra\Repository\Empresa\ClienteRepository;
use App\Infra\Repository\Empresa\ExistenciaStockRepository;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Repositories\Empresa\TraitChavesEmpresa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpseclib\Crypt\RSA;
use phpseclib\Crypt\RSA as Crypt_RSA;

class SimuladorFaturacao
{
    use TraitChavesEmpresa;
    private ClienteRepository $clienteRepository;
    private ExistenciaStockRepository $existenciaStockRepository;
    private FaturaRepository $faturaRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->clienteRepository = $repositoryFactory->createClienteRepository();
        $this->existenciaStockRepository = $repositoryFactory->createExistenciaStockRepository();
        $this->faturaRepository = $repositoryFactory->createFaturaRepository();
    }

    public function execute($request)
    {
        $totalEntregue = !is_numeric($request->totalEntregue)?0:$request->totalEntregue??0;
        $totalMulticaixa = !is_numeric($request->totalMulticaixa)?0:$request->totalMulticaixa??0;
        $totalCash = !is_numeric($request->totalCash)?0:$request->totalCash??0;
        $totalPagar = !is_numeric($request->totalPagar)?0:$request->totalPagar??0;

        $fatura = new Fatura(
            $request->clienteId,
            $request->nomeCliente,
            $request->nifCliente,
            $request->emailCliente,
            $request->numeroCartaoCliente,
            $request->aplicadoCartaoCliente,
            $request->saldoCliente,
            $request->telefoneCliente,
            $request->enderecoCliente,
            $request->contaCorrenteCliente,
            $request->formaPagamentoId,
            $request->armazemId,
            $request->desconto,
            $request->isRetencao,
            $request->tipoDocumento,
            $request->tipoFolha,
            $totalEntregue,
            $totalMulticaixa,
            $totalCash,
            $request->observacao,
        );
        foreach ($request->items as $item) {
            $item = (object) $item;
            $isStock = $this->existenciaStockRepository->isStock($item, $item->armazemId);
            if (!$isStock && $item->isEstocavel == 'Sim' && $request->tipoDocumento != 3) {
                throw new \Error("Quantidade indisponível no estoque");
            }
            $faturaItem = new FaturaItem(
                $item->produtoId,
                $item->armazemId,
                $item->nomeProduto,
                $item->codigoProduto,
                $item->produtoCartaGarantia,
                $item->tempoGarantiaProduto,
                $item->tipoGarantia,
                $item->precoVendaProduto,
                $item->pvp,
                $item->precoCompraProduto,
                $item->quantidadeStock,
                $item->isEstocavel,
                $item->quantidadeMinima,
                $item->quantidadeCritica,
                $item->taxaIva,
                $item->iva,
                $item->quantidade,
                $item->desconto,
                $request->desconto
            );
            $fatura->addItem($faturaItem);
        }

        return $fatura;

        $isExisteDocumento = $this->faturaRepository->verificarSeExisteDocumentoSuperiorDataAtual();
        if(!$isExisteDocumento) throw new \Error("Existe um documento com data superior á data actual");

        $facturas = $this->faturaRepository->pegarUltimaFactura($fatura->getTipoDocumento());
        /**
         * hashAnterior inicia vazio
         */
        $hashAnterior = "";
        if ($facturas) {
            $data_factura = Carbon::createFromFormat('Y-m-d H:i:s', $facturas->created_at);
            $hashAnterior = $facturas->hashValor;
        } else {
            $data_factura = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        }

        //ManipulaÃ§Ã£o de datas: data da factura e data actual
        //$data_factura = Carbon::createFromFormat('Y-m-d H:i:s', $facturas->created_at);
        $datactual = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));

        /*Recupera a sequÃªncia numÃ©rica da Ãºltima factura cadastrada no banco de dados e adiona sempre 1 na sequÃªncia caso o ano da afctura seja igual ao ano actual;
        E reinicia a sequÃªncia numÃ©rica caso se constate que o ano da factura Ã© inferior ao ano actual.*/
        if ($data_factura->diffInYears($datactual) == 0) {
            if ($facturas) {
                $data_factura = Carbon::createFromFormat('Y-m-d H:i:s', $facturas->created_at);
                $numSequenciaFactura = intval($facturas->numSequenciaFactura) + 1;
            } else {
                $data_factura = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                $numSequenciaFactura = 1;
            }
        } else if ($data_factura->diffInYears($datactual) > 0) {
            $numSequenciaFactura = 1;
        }

        if ($facturas) {
            $sequenciaDocumento = DB::connection('mysql2')->table('sequencias_documentos')
                ->where('tipo_documento', $fatura->getTipoDocumento())
                ->where('empresa_id', auth()->user()->empresa_id)
                ->orderBy('id', 'DESC')
                ->first();
            if ($sequenciaDocumento) {
                if ($sequenciaDocumento->sequencia > $numSequenciaFactura) {
                    $numSequenciaFactura = $sequenciaDocumento->sequencia;
                }
            }

        } else {
            $sequenciaDocumento = DB::connection('mysql2')->table('sequencias_documentos')
                ->where('tipo_documento', $fatura->getTipoDocumento())
                ->where('empresa_id', auth()->user()->empresa_id)
                ->orderBy('id', 'DESC')
                ->first();
            if ($sequenciaDocumento) {
                if ($sequenciaDocumento->sequencia > $numSequenciaFactura) {
                    $numSequenciaFactura = $sequenciaDocumento->sequencia;
                }
            }
        }

        $TIPO_DOC_FATURA_RECIBO = 1;
        $TIPO_DOC_FATURA = 2;
        $TIPO_DOC_FATURA_PROFORMA = 3;

        /*Cria uma sÃ©rie de numerÃ§Ã£o para cada factura, variando de acordo o tipo de factura, a o ano actual e numero sequencial da factura */
        if ($fatura->getTipoDocumento() == $TIPO_DOC_FATURA) {
            $diasVencimentoFactura = $this->faturaRepository->diasVencimentoFactura();
            $numeracaoFactura = 'FT ' . $this->faturaRepository->mostrarSerieDocumento() . date('Y') . '/' . $numSequenciaFactura; //retirar somente 3 primeiros caracteres na facturaSerie da factura: substr('abcdef', 0, 3);
            $data_vencimento = Carbon::now()->addDays($diasVencimentoFactura);
        }
        if ($fatura->getTipoDocumento() == $TIPO_DOC_FATURA_RECIBO) {
            $numeracaoFactura = 'FR ' . $this->faturaRepository->mostrarSerieDocumento() . date('Y') . '/' . $numSequenciaFactura; //retirar somente 3 primeiros caracteres na facturaSerie da factura: substr('abcdef', 0, 3);
        }
        if ($fatura->getTipoDocumento() == $TIPO_DOC_FATURA_PROFORMA) {
            $diasVencimentoFactura = $this->faturaRepository->diasVencimentoFacturaProforma();
            $data_vencimento = Carbon::now()->addDays($diasVencimentoFactura);
            $numeracaoFactura = 'PP ' . $this->faturaRepository->mostrarSerieDocumento() . date('Y') . '/' . $numSequenciaFactura; //retirar somente 3 primeiros caracteres na facturaSerie da factura: substr('abcdef', 0, 3);
            $convertidoFactura = 1; //status não convertido
        }
        $rsa = new Crypt_RSA(); //Algoritimo RSA

        $privatekey = $this->pegarChavePrivada();
        $publickey = $this->pegarChavePublica();

        // Lendo a private key
        $rsa->loadKey($privatekey);

        $plaintext = str_replace(date(' H:i:s'), '', $datactual) . ';' . str_replace(' ', 'T', $datactual) . ';' . $numeracaoFactura . ';' . number_format($fatura->totalPagar() + $fatura->totalRetencao(), 2, ".", "") . ';' . $hashAnterior;

        // HASH
        $hash = 'sha1'; // Tipo de Hash
        $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

        //ASSINATURA
        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
        $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)

        // Lendo a public key
        $rsa->loadKey($publickey);


        return $this->faturaRepository->emitirDocumento($fatura);
    }


}
