<?php

namespace App\Application\UseCase\Empresa\Faturacao;

use App\Application\UseCase\Empresa\CartaoCliente\AtualizaSaldoCartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\GetBonuCartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\GetCartaoClientePeloClienteId;
use App\Application\UseCase\Empresa\CartaoCliente\GetCartaoClientePeloNumero;
use App\Application\UseCase\Empresa\CartaoCliente\IsValidoCartaoCliente;
use App\Application\UseCase\Empresa\Estoque\AtualizarEstoque;
use App\Application\UseCase\Empresa\HistoricoCartaoCliente\AtualizarHistoricoCartaoCliente;
use App\Application\UseCase\Empresa\HistoricoCartaoCliente\CadastrarHistoricoCartaoCliente;
use App\Application\UseCase\Empresa\Licencas\VerificarUserLogadoLicencaGratis;
use App\Application\UseCase\Empresa\NotaEntrega\GetHabilitadoNotaEntrega;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Domain\Entity\Empresa\CartaoCliente;
use App\Domain\Entity\Empresa\CartaoCliente\ExtratoCartaoCliente;
use App\Domain\Entity\Empresa\Faturacao\FaturaEmitida;
use App\Domain\Entity\Empresa\Faturacao\FaturaItemEmitida;
use App\Domain\Factory\Empresa\RepositoryFactory;
use App\Http\Controllers\TypeInvoice;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\CartaoClienteRepository;
use App\Infra\Repository\Empresa\ClienteRepository;
use App\Infra\Repository\Empresa\ExistenciaStockRepository;
use App\Infra\Repository\Empresa\ExtratoCartaoClienteRepository;
use App\Infra\Repository\Empresa\FaturaRepository;
use App\Infra\Repository\Empresa\Relatorios\TraitRelatorioFaturacaoJasper;
use App\Infra\Repository\Empresa\SequenciaFaturaRepository;
use App\Repositories\Empresa\TraitChavesEmpresa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;
use phpseclib\Crypt\RSA;
use phpseclib\Crypt\RSA as Crypt_RSA;

class EmitirDocumento
{
    public $PAGAMENTO_DUPLO = 6;
    public $FATURA_RECIBO = 1;
    public $FATURA = 2;
    public $FATURA_PROFORMA = 3;

    use TraitChavesEmpresa;

    private ClienteRepository $clienteRepository;
    private ExistenciaStockRepository $existenciaStockRepository;
    private FaturaRepository $faturaRepository;
    private SequenciaFaturaRepository $sequenciaFaturaRepository;
    private CartaoClienteRepository $cartaoClienteRepository;
    private ExtratoCartaoClienteRepository $extratoCartaoClienteRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->clienteRepository = $repositoryFactory->createClienteRepository();
        $this->cartaoClienteRepository = $repositoryFactory->createCartaoClienteRepository();
        $this->existenciaStockRepository = $repositoryFactory->createExistenciaStockRepository();
        $this->faturaRepository = $repositoryFactory->createFaturaRepository();
        $this->sequenciaFaturaRepository = $repositoryFactory->createSequenciaFaturaRepository();
        $this->extratoCartaoClienteRepository = $repositoryFactory->createExtratoCartaoClienteRepository();
    }

    public function execute(Request $request)
    {
        if (!count($request->items)) throw new \Error("Adiciona item no carrinho");

        if (!$request->aplicadoCartaoCliente && $request->totalEntregue > 0 && $request->totalMulticaixa > 0) {
            $request->formaPagamentoId = 6;
            if (!$request->totalMulticaixa || !$request->totalEntregue) {
                throw new \Error("Informe o valor multicaixa e o valor entregue");
            }
            $multicaixa = !is_numeric($request->totalMulticaixa) ? 0 : $request->totalMulticaixa ?? 0;
            $cash = !is_numeric($request->totalEntregue) ? 0 : $request->totalEntregue ?? 0;
            $totalMulticaixaEcash = $multicaixa + $cash;
            if ($request->totalPagar > $totalMulticaixaEcash) throw new \Error("Total multicaixa e total entregue insuficiente");
        } else if (!$request->aplicadoCartaoCliente && (!$request->totalEntregue || $request->totalEntregue <= 0) && $request->totalMulticaixa > 0) {
            $request->formaPagamentoId = 3;
        }
        if ($request->formaPagamentoId == TypeInvoice::FACTURA_RECIBO && !$request->aplicadoCartaoCliente && $request->totalMulticaixa <= 0 && $request->totalEntregue <= 0) {
            throw new \Error("Informe e o valor entregue");
        }
        if ($this->PAGAMENTO_DUPLO == $request->formaPagamentoId) {
            if (!$request->totalMulticaixa || !$request->totalEntregue) {
                throw new \Error("Informe e o valor entregue");
            }
            $multicaixa = !is_numeric($request->totalMulticaixa) ? 0 : $request->totalMulticaixa ?? 0;
            $cash = abs($request->totalPagar - $multicaixa);
//            $cash = !is_numeric($request->totalEntregue) ? 0 : $request->totalEntregue ?? 0;
            $totalMulticaixaEcash = $multicaixa + $cash;
            if ($request->totalPagar > $totalMulticaixaEcash) throw new \Error("Total multicaixa e total entregue insuficiente");
        }
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

//        $sequenciaDocumento = $this->sequenciaFaturaRepository->getUltimaSequenciaFatura($request->tipoDocumento);
//        if ($sequenciaDocumento) {
//            if ($sequenciaDocumento->sequencia > $numSequenciaFactura) {
//                $numSequenciaFactura = $sequenciaDocumento->sequencia;
//            }
//        }

        $getYearNow = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $getYearNow = $getYearNow->execute('ano_de_faturacao');
        $yearNow = Carbon::parse(Carbon::now())->format('Y');

        if($getYearNow){
            $yearNow = $getYearNow->valor;
        }
        /*Cria uma sÃ©rie de numerÃ§Ã£o para cada factura, variando de acordo o tipo de factura, a o ano actual e numero sequencial da factura */
        if ($request->tipoDocumento == $this->FATURA) {
            $diasVencimentoFactura = $this->faturaRepository->diasVencimentoFactura();
            $numeracaoFactura = 'FT ' . $this->faturaRepository->mostrarSerieDocumento() . $yearNow . '/' . $numSequenciaFactura; //retirar somente 3 primeiros caracteres na facturaSerie da factura: substr('abcdef', 0, 3);
            $dataVencimento = Carbon::now()->addDays($diasVencimentoFactura);
            $statusFatura = 1; //Divida
        }
        if ($request->tipoDocumento == $this->FATURA_RECIBO) {
            $numeracaoFactura = 'FR ' . $this->faturaRepository->mostrarSerieDocumento() . $yearNow . '/' . $numSequenciaFactura; //retirar somente 3 primeiros caracteres na facturaSerie da factura: substr('abcdef', 0, 3);
            $statusFatura = 2;//Pago
            $dataVencimento = null;
        }
        if ($request->tipoDocumento == $this->FATURA_PROFORMA) {
            $diasVencimentoFactura = $this->faturaRepository->diasVencimentoFacturaProforma();
            $dataVencimento = Carbon::now()->addDays($diasVencimentoFactura);
            $numeracaoFactura = 'PP ' . $this->faturaRepository->mostrarSerieDocumento() . $yearNow . '/' . $numSequenciaFactura; //retirar somente 3 primeiros caracteres na facturaSerie da factura: substr('abcdef', 0, 3);
            $convertidoFactura = 1; //status não convertido
            $statusFatura = null;
        }

        $rsa = new Crypt_RSA(); //Algoritimo RSA

        $privatekey = $this->pegarChavePrivada();
        $publickey = $this->pegarChavePublica();

        // Lendo a private key
        $rsa->loadKey($privatekey);

        $plaintext = str_replace(date(' H:i:s'), '', $datactual) . ';' . str_replace(' ', 'T', $datactual) . ';' . $numeracaoFactura . ';' . number_format($request->totalPagar + $request->totalRetencao, 2, ".", "") . ';' . $hashAnterior;

        // HASH
        $hash = 'sha1'; // Tipo de Hash
        $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

        //ASSINATURA
        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
        $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)
        $hashValor = base64_encode($signaturePlaintext);

        $troco = 0;
        $totalMulticaixaTotalEntregue = (!$request['totalMulticaixa'] ? 0 : $request['totalMulticaixa']) + (!$request['totalEntregue'] ? 0 : $request['totalEntregue']);
        $valorPagar = !$request['totalPagar'] ? 0 : $request['totalPagar'];
        $totalEntregue = !$request['totalEntregue'] ? 0 : $request['totalEntregue'];
        $totalMulticaixa = !$request['totalMulticaixa'] ? 0 : $request['totalMulticaixa'];

        $saldoAnterior = 0;
        $saldoAtual = 0;
        $bonus = 0;
        $valorBonus = 0;
        $totalDescontarCartao = 0;
        $cartaoValido = false;


        if ($request['tipoDocumento'] == TypeInvoice::FACTURA || $request['tipoDocumento'] == TypeInvoice::FACTURA_PROFORMA) {
            $troco = 0;
            $request['totalEntregue'] = 0;
        } else if ($request['formaPagamentoId'] == 6 && $totalMulticaixaTotalEntregue > $valorPagar) { //pagamento duplo
            $troco = (($request['totalMulticaixa'] ? $request['totalMulticaixa'] : 0) + ($request['totalEntregue']) - ($request['totalPagar'] ? $request['totalPagar'] : 0)) ?? 0;
        } else if ($request['formaPagamentoId'] == 1 && $totalEntregue > $valorPagar) { //pagamento numerario
            $troco = ($request['totalEntregue'] ? $request['totalEntregue'] : 0) - ($request['totalPagar'] ? $request['totalPagar'] : 0) ?? 0;
        }
        $cartaoClientePeloNumero = new GetCartaoClientePeloNumero(new DatabaseRepositoryFactory());
        $cartaoClientePeloCliente = new GetCartaoClientePeloClienteId(new DatabaseRepositoryFactory());
        $cartaoCliente = $request->numeroCartaoCliente ? $cartaoClientePeloNumero->execute($request->numeroCartaoCliente) : $cartaoClientePeloCliente->execute($request->clienteId);

        if ($cartaoCliente) {
            $saldoAnterior = $cartaoCliente->saldo;
            $saldoAtual = $cartaoCliente->saldo;
            if ($cartaoCliente && ($request->formaPagamentoId == 1 || $request->formaPagamentoId == 6 || $request->formaPagamentoId == 3)) {

                $getValorEstipuladoAplicarBonus = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
                $valorEstipuladoBonus = $getValorEstipuladoAplicarBonus->execute('valor_estipulado_bonus');
                $valorEstipuladoAplicacaoBonus = $valorEstipuladoBonus->valor;
                if ($valorPagar >= $valorEstipuladoAplicacaoBonus) {
                    $cartaoValido = new IsValidoCartaoCliente(new DatabaseRepositoryFactory());
                    $cartaoValido = $cartaoValido->execute($cartaoCliente->numeroCartao);
                    if (!$cartaoValido && $request->numeroCartaoCliente != null) throw new \Error("Cartão está expirado");
                    $bonus = new GetBonuCartaoCliente(new DatabaseRepositoryFactory());
                    $bonuData = $bonus->execute();
                    if ($totalMulticaixaTotalEntregue > 0) {
                        if ($bonuData) {
                            $bonus = $bonuData->bonus;
                        } else {
                            $bonus = 0;
                        }
                    } else {
                        $bonus = 0;
                    }
                    if ($totalMulticaixaTotalEntregue >= $valorPagar) {
                        $totalDescontarCartao = 0;
                    } else {
                        if ($totalEntregue > 0 || $totalMulticaixa > 0) {
                            $totalDescontarCartao = $valorPagar - $totalMulticaixaTotalEntregue;
                        } else {
                            $totalDescontarCartao = $valorPagar;
                        }
                    }
                    if ($saldoAtual < $totalDescontarCartao) throw new \Error("Saldo do cartão insuficiente");
                    if ($totalDescontarCartao > 0) {
                        $valorBonus = 0;
                        $saldoAtual = ($saldoAnterior - $totalDescontarCartao) + $valorBonus;
                        $operacao = 2;
                        if ($cartaoValido && $request->formaPagamentoId !== TypeInvoice::FACTURA && $request->formaPagamentoId !== TypeInvoice::FACTURA_PROFORMA) {
                            $this->cadastrarHistorioCartaoCliente($cartaoCliente->clienteId, $bonus, $operacao, $saldoAnterior, $saldoAtual, $valorBonus, $totalDescontarCartao, $numeracaoFactura, false, $valorPagar);
                        }
                    }
                    $totalMulticaixaTotalEntregue = $totalMulticaixaTotalEntregue - $troco;
                    $valorBonus = ($totalMulticaixaTotalEntregue * $bonus) / 100;
                    $saldoAtual = $saldoAtual + $valorBonus;
                    $operacao = 1;
                    if ($cartaoValido && $valorBonus > 0 && $request->formaPagamentoId !== TypeInvoice::FACTURA && $request->formaPagamentoId !== TypeInvoice::FACTURA_PROFORMA) {
                        $updateBonus = true;
                        if ($totalMulticaixaTotalEntregue >= $valorPagar) {
                            $this->cadastrarHistorioCartaoCliente($cartaoCliente->clienteId, $bonus, $operacao, $saldoAnterior, $saldoAtual, $valorBonus, $totalDescontarCartao, $numeracaoFactura, $updateBonus, $valorPagar);
                        }
                        $this->atualizarHistorioCartaoCliente($cartaoCliente->clienteId, $bonus, $operacao, $saldoAnterior, $saldoAtual, $valorBonus, $totalDescontarCartao, $numeracaoFactura, $updateBonus, $valorPagar);
                    }
                }
            }
            if ($cartaoValido) {
                $atualizarSaldoCartao = new AtualizaSaldoCartaoCliente(new DatabaseRepositoryFactory());
                $atualizarSaldoCartao->execute($cartaoCliente->numeroCartao, $saldoAtual);
            }
        }

        // Lendo a public key
        $rsa->loadKey($publickey);
        $numeroItems = count($request->items);
        $retificado = "Não";
        $canalId = 2;
        $statusId = 1;
        $moedaId = 1;
        $totalMulta = 0;

        $novaEntrega = new GetHabilitadoNotaEntrega(new DatabaseRepositoryFactory());
        $isNovaEntrega = $novaEntrega->execute();

        $faturaEmitida = new FaturaEmitida(
            $request->totalPrecoFactura,
            $request->totalPagar,
            $request->totalEntregue,
            $request->totalMulticaixa,
            $request->totalCash,
            $request->totalTroco,
            $request->totalIncidencia,
            $request->totalExtenso,
            $plaintext,
            $moedaId,
            $request->totalDesconto,
            $request->totalIva,
            $totalMulta,
            $request->nomeCliente,
            $bonus,
            $valorBonus,
            $saldoAnterior,
            $request->aplicadoCartaoCliente,
            $totalDescontarCartao,
            $cartaoCliente ? $cartaoCliente->numeroCartao : null,
            $saldoAtual,
            $request->telefoneCliente,
            $request->nifCliente,
            $request->emailCliente,
            $request->enderecoCliente,
            $request->contaCorrenteCliente,
            $numeroItems,
            $request->tipoDocumento,
            $request->tipoFolha,
            $request->totalRetencao,
            $numSequenciaFactura,
            $numeracaoFactura,
            $hashValor,
            $retificado,
            $request->formaPagamentoId,
            $request->armazemId,
            $request->clienteId,
            $canalId,
            $statusId,
            $statusFatura,
            $dataVencimento,
            $request->observacao,
            $isNovaEntrega
        );
        $outputFatura = $this->faturaRepository->emitirDocumento($faturaEmitida);
        if (!$outputFatura) throw  new \Error("Erro ao emitir fatura");
        foreach ($request->items as $faturaItem) {
            $faturaItem = (object)$faturaItem;
            $subTotalRetencao = 0;

            $tempoGarantiaProduto = $faturaItem->produtoCartaGarantia == "Y" ? $faturaItem->tempoGarantiaProduto . " " . $faturaItem->tipoGarantia : null;

            $faturaItem = new FaturaItemEmitida(
                $faturaItem->nomeProduto,
                $faturaItem->precoVendaProduto,
                $faturaItem->produtoCartaGarantia,
                $tempoGarantiaProduto,
                $faturaItem->precoCompraProduto,
                $faturaItem->quantidade,
                $faturaItem->desconto,
                $faturaItem->subTotalDesconto,
                $faturaItem->quantidadeStock,
                $faturaItem->subTotalIncidencia,
                $subTotalRetencao,
                $faturaItem->taxaIva,
                $faturaItem->subTotalTaxaIva,
                $faturaItem->subTotalPrecoProduto,
                $faturaItem->produtoId,
                $outputFatura->id,
                $faturaItem->armazemId,
                $faturaItem->isEstocavel,
                $faturaItem->quantidadeStock,
                $faturaItem->quantidadeMinima,
                $faturaItem->quantidadeCritica,
            );

            $faturaItemData = $this->faturaRepository->salvarItemDocumento($faturaItem);
            if(!$faturaItemData) throw  new \Error("Erro ao salvar item");
            if($faturaItem->getIsEstocavel() == 'Sim' && $faturaEmitida->getTipoDocumento() != TypeInvoice::FACTURA_PROFORMA){
                $atualizarStock = new AtualizarEstoque(new DatabaseRepositoryFactory());
                $quantidadeAtual = $faturaItem->getQuantidadeAnteriorProduto() - $faturaItem->getQuantidadeProduto();
                $atualizarStock->execute(new Request([
                    'produtoId' => $faturaItem->getProdutoId(),
                    'quantidadeAnterior' =>$faturaItem->getQuantidadeAnteriorProduto(),
                    'quantidadeNova' => $quantidadeAtual,
                    'armazemId' =>  $faturaItem->getArmazemId(),
                    'descricao' => null,
                    'centroCustoId' => session()->get('centroCustoId')
                ]));
            }
        }
        return $outputFatura;

    }

    private function atualizarHistorioCartaoCliente($clienteId, $bonus, $operacao, $saldoAnterior, $saldoAtual, $valorBonus, $valorDescontarCartao, $numeracaoFatura, $updateBonus = false, $valorPagar)
    {
        $cadastrarHistorico = new AtualizarHistoricoCartaoCliente(new DatabaseRepositoryFactory());
        return $cadastrarHistorico->execute(new Request([
            'clienteId' => $clienteId,
            'bonus' => $bonus,
            'operacao' => $operacao,
            'saldo_anterior' => $saldoAnterior,
            'saldo_atual' => $saldoAtual,
            'valorBonus' => $valorBonus,
            'valorDescontarCartao' => $valorDescontarCartao,
            'documetoReferente' => $numeracaoFatura.'('. number_format($valorPagar, 2, ',', '.').')',
            'updateBonus' => $updateBonus
        ]));
    }

    private function cadastrarHistorioCartaoCliente($clienteId, $bonus, $operacao, $saldoAnterior, $saldoAtual, $valorBonus, $totalDescontarCartao, $numeracaoFatura, $updateBonus = false, $valorPagar)
    {
        $cadastrarHistorico = new CadastrarHistoricoCartaoCliente(new DatabaseRepositoryFactory());
        return $cadastrarHistorico->execute(new Request([
            'clienteId' => $clienteId,
            'bonus' => $bonus,
            'operacao' => $operacao,
            'saldo_anterior' => $saldoAnterior,
            'saldo_atual' => $saldoAtual,
            'valorBonus' => $valorBonus,
            'valorDescontarCartao' => $totalDescontarCartao,
            'documetoReferente' => $numeracaoFatura.'('. number_format($valorPagar, 2, ',', '.').')',
            'updateBonus' => $updateBonus
        ]));
    }


}
