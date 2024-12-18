<?php

namespace App\Application\UseCase\VendasOnline\ValidacaoPagamento;

use App\Application\UseCase\Empresa\CartaoCliente\AtualizaSaldoCartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\GetBonuCartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\GetCartaoClientePeloClienteId;
use App\Application\UseCase\Empresa\CartaoCliente\GetCartaoClientePeloNumero;
use App\Application\UseCase\Empresa\CartaoCliente\IsValidoCartaoCliente;
use App\Application\UseCase\Empresa\HistoricoCartaoCliente\AtualizarHistoricoCartaoCliente;
use App\Application\UseCase\Empresa\HistoricoCartaoCliente\CadastrarHistoricoCartaoCliente;
use App\Application\UseCase\Empresa\NotaEntrega\GetHabilitadoNotaEntrega;
use App\Domain\Factory\VendasOnline\RepositoryFactory;
use App\Http\Controllers\TypeInvoice;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\VendasOnline\FaturaRepository;
use App\Repositories\Empresa\TraitChavesEmpresa;
use App\Repositories\Empresa\TraitSerieDocumento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Keygen\Keygen;
use phpseclib\Crypt\RSA;
use NumberFormatter;

class EmitirFaturaReciboVendaOnline
{
    use TraitSerieDocumento;
    use TraitChavesEmpresa;

    private FaturaRepository $faturaRepository;

    public function __construct(RepositoryFactory $repositoryFactory)
    {
        $this->faturaRepository = $repositoryFactory->createFaturaRepository();
    }

    public function execute($pagamento)
    {
        $facturas = $this->pegarUltimaFactura(TypeInvoice::FACTURA_RECIBO);

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
        $factura['data_vencimento'] = NULL;
        $numeracaoFactura = 'FR ' . $this->mostrarSerieDocumento() . date('Y') . '/' . $numSequenciaFactura; //retirar somente 3 primeiros caracteres na facturaSerie da factura: substr('abcdef', 0, 3);

        $rsa = new RSA(); //Algoritimo RSA

        $privatekey = $this->pegarChavePrivada();
        $publickey = $this->pegarChavePublica();

        // Lendo a private key
        $rsa->loadKey($privatekey);

        $plaintext = str_replace(date(' H:i:s'), '', $datactual) . ';' . str_replace(' ', 'T', $datactual) . ';' . $numeracaoFactura . ';' . number_format($pagamento['totalPagamento'], 2, ".", "") . ';' . $hashAnterior;


        // HASH
        $hash = 'sha1'; // Tipo de Hash
        $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

        //ASSINATURA
        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
        $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenaÃ§Ãµes)

        // Lendo a public key
        $rsa->loadKey($publickey);

        $f = new NumberFormatter("pt", NumberFormatter::SPELLOUT);

        $cliente = DB::table('clientes')
            ->where('user_id', $pagamento['userId'])
            ->first();


        $troco = 0;
        $totalMulticaixa = $pagamento['totalPagamento'];
        $totalEntregue = 0;
        $totalMulticaixaTotalEntregue = $totalMulticaixa + $totalEntregue;
        $valorPagar = $pagamento['totalPagamento'] ?? 0;

        $saldoAnterior = 0;
        $saldoAtual = 0;
        $bonus = 0;
        $valorBonus = 0;
        $totalDescontarCartao = 0;
        $cartaoValido = false;


        $cartaoClientePeloNumero = new GetCartaoClientePeloNumero(new DatabaseRepositoryFactory());
        $cartaoClientePeloCliente = new GetCartaoClientePeloClienteId(new DatabaseRepositoryFactory());
        $cartaoCliente = isset($pagamento['numeroCartaoCliente']) ? $cartaoClientePeloNumero->execute($pagamento['numeroCartaoCliente']) : $cartaoClientePeloCliente->execute($cliente->id);

        if ($cartaoCliente) {
            $saldoAnterior = $cartaoCliente->saldo;
            $saldoAtual = $cartaoCliente->saldo;


            if ($cartaoCliente && ($pagamento['formaPagamentoId'] == 3 || $pagamento['formaPagamentoId'] == 4 || $pagamento['formaPagamentoId'] == 5)) {
                $cartaoValido = new IsValidoCartaoCliente(new DatabaseRepositoryFactory());
                $cartaoValido = $cartaoValido->execute($cartaoCliente->numeroCartao);
                if (!$cartaoValido && $pagamento['numeroCartaoCliente'] != null) return response()->json(['data' => null, 'message' => "Cartão está expirado"]);

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
                if ($saldoAtual < $totalDescontarCartao) return response()->json(['data' => null, 'message' => "Saldo do cartão insuficiente"]);
                if ($totalDescontarCartao > 0) {
                    $valorBonus = 0;
                    $saldoAtual = ($saldoAnterior - $totalDescontarCartao) + $valorBonus;
                    $operacao = 2;
                    if ($cartaoValido && $pagamento['formaPagamentoId'] !== TypeInvoice::FACTURA && $pagamento['formaPagamentoId'] !== TypeInvoice::FACTURA_PROFORMA) {
                        $this->cadastrarHistorioCartaoCliente($cartaoCliente->clienteId, $bonus, $operacao, $saldoAnterior, $saldoAtual, $valorBonus, $totalDescontarCartao, $numeracaoFactura, false, $valorPagar);
                    }
                }
                $totalMulticaixaTotalEntregue = $totalMulticaixaTotalEntregue - $troco;
                $valorBonus = ($totalMulticaixaTotalEntregue * $bonus) / 100;
                $saldoAtual = $saldoAtual + $valorBonus;
                $operacao = 1;
                if ($cartaoValido && $valorBonus > 0 && $pagamento['formaPagamentoId'] !== TypeInvoice::FACTURA && $pagamento['formaPagamentoId'] !== TypeInvoice::FACTURA_PROFORMA) {
                    $updateBonus = true;
                    if ($totalMulticaixaTotalEntregue >= $valorPagar) {
                        $this->cadastrarHistorioCartaoCliente($cartaoCliente->clienteId, $bonus, $operacao, $saldoAnterior, $saldoAtual, $valorBonus, $totalDescontarCartao, $numeracaoFactura, $updateBonus, $valorPagar);
                    }
                    $this->atualizarHistorioCartaoCliente($cartaoCliente->clienteId, $bonus, $operacao, $saldoAnterior, $saldoAtual, $valorBonus, $totalDescontarCartao, $numeracaoFactura, $updateBonus, $valorPagar);
                }
            }
            if ($cartaoValido) {
                $atualizarSaldoCartao = new AtualizaSaldoCartaoCliente(new DatabaseRepositoryFactory());
                $atualizarSaldoCartao->execute($cartaoCliente->numeroCartao, $saldoAtual);
            }
        }
        $novaEntrega = new GetHabilitadoNotaEntrega(new DatabaseRepositoryFactory());
        $isNovaEntrega = $novaEntrega->execute();
        $novaEntrega = isset($isNovaEntrega) && $isNovaEntrega->valor == 'sim' ? 'Y' : 'Y';


        $facturaId = DB::table('facturas')->insertGetId([
            'total_preco_factura' => $valorPagar ?? 0,
            'valor_a_pagar' => $valorPagar?? 0,
            'valor_entregue' => 0,
            'valor_multicaixa' => $valorPagar ?? 0,
            'valor_cash' => 0,
            'data_vencimento' => null,
            'troco' => 0,
            'valor_extenso' => $f->format($valorPagar ?? 0),
            'pagamento_venda_online_id'=>$pagamento->id,
            'codigo_moeda' =>  1,
            'desconto' => 0,
            'total_iva' => 0,
            'multa' => 0,
            'nome_do_cliente' => $pagamento['nomeUserEntrega'] ?? 'Consumidor final',
            'telefone_cliente' => $pagamento['telefoneUserEntrega'] ?? NULL,
            'nif_cliente' => $cliente->nif ?? '999999999',
            'email_cliente' => $pagamento['emailEntrega'] ?? NULL,
            'endereco_cliente' => $pagamento['enderecoEntrega'] ?? NULL,
            'conta_corrente_cliente' => $cliente->conta_corrente,
            'numeroItems' => count($pagamento['pagamentoVendasOnlineItems']) ?? 1,
            'tipo_documento' => 1,
            'tipoFolha' => 'A4',
            'retencao' => 0,
            'texto_hash' => $plaintext,
            'nextFactura' => mb_strtoupper(Keygen::numeric(9)->generate()),
            'faturaReference' => mb_strtoupper(Keygen::numeric(9)->generate()),
            'numSequenciaFactura' => $numSequenciaFactura,
            'numeracaoFactura' => $numeracaoFactura,
            'hashValor' => base64_encode($signaturePlaintext),
            'retificado' => 'Não',
            'formas_pagamento_id' => $pagamento['formaPagamentoId'],
            'observacao' => NULL,
            'descricao' => NULL,
            'armazen_id' => NULL,
            'cliente_id' => $cliente->id,
            'empresa_id' => auth()->user()->empresa_id,
            'notaEntrega' => $novaEntrega,
            'canal_id' => $factura['canal_id'] ?? 4,
            'status_id' => $factura['status_id'] ?? 1,
            'user_id' => auth()->user()->id,
            'venda_online' => 'Y',
            'operador' => auth()->user()->name,
            'convertidoFactura' => TypeInvoice::CONVERTIDO,
            'numeracaoProforma' => NULL,
            'total_incidencia' => $valorPagar,
            'saldoAnteriorCartaoCliente' => $saldoAnterior ?? 0,
            'saldoAtualCartaoCliente' => $saldoAtual ?? 0,
            'numeroCartaoCliente' => $cartaoCliente ? $cartaoCliente->numeroCartao : null,
            'bonusDescontoCartaoCliente' => $bonus ?? 0,
            'centroCustoId' => session()->get('centroCustoId'),
            'valorBonusCartaoCliente' => $valorBonus ?? 0,
            'totalDescontarCartao' => $totalDescontarCartao ?? 0,
            'tipo_user_id' => 2,
            'statusFactura' =>TypeInvoice::STATUS_PAGO,
            'anulado' =>  1,
            'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')),
            'updated_at' => Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'))
        ]);
        foreach ($pagamento['pagamentoVendasOnlineItems'] as $item) {
            $produto = DB::table('produtos')->where('id', $item['produtoId'])->first();
            DB::connection('mysql2')->table('factura_items')->insert([
                'descricao_produto' => $item['nomeProduto'],
                'preco_compra_produto' =>  $produto->preco_compra,
                'preco_venda_produto' => $item['precoVendaProduto'],
                'produtoCartaGarantia' => $produto->cartaGarantia,
                'quantidade_produto' => $item['quantidade'],
                'quantidade_anterior' => 0,
                'desconto_produto' => 0,
                'incidencia_produto' => $item['subtotal'],
                'retencao_produto' => 0,
                'iva_produto' => 0,
                'total_preco_produto' => $item['subtotal'] ?? 0,
                'produto_id' => $item['produtoId'],
                'factura_id' => $facturaId,
            ]);
        }

        return $facturaId;
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
            'documetoReferente' => $numeracaoFatura . '(' . number_format($valorPagar, 2, ',', '.') . ')',
            'updateBonus' => $updateBonus
        ]));
    }

    private function atualizarHistorioCartaoCliente($clienteId, $bonus, $operacao, $saldoAnterior, $saldoAtual, $valorBonus, $totalDescontarCartao, $numeracaoFatura, $updateBonus = false, $valorPagar)
    {
        $cadastrarHistorico = new AtualizarHistoricoCartaoCliente(new DatabaseRepositoryFactory());
        return $cadastrarHistorico->execute(new Request([
            'clienteId' => $clienteId,
            'bonus' => $bonus,
            'operacao' => $operacao,
            'saldo_anterior' => $saldoAnterior,
            'saldo_atual' => $saldoAtual,
            'valorBonus' => $valorBonus,
            'valorDescontarCartao' => $totalDescontarCartao,
            'documetoReferente' => $numeracaoFatura . '(' . number_format($valorPagar, 2, ',', '.') . ')',
            'updateBonus' => $updateBonus
        ]));
    }

    public function pegarUltimaFactura($tipoDocumento)
    {
        $yearNow = Carbon::parse(Carbon::now())->format('Y');

        return DB::connection('mysql2')->table('facturas')->where('empresa_id', auth()->user()->empresa_id)
            ->where('created_at', 'like', '%' . $yearNow . '%')
            ->where('tipo_documento', $tipoDocumento)
            ->where('numeracaoFactura', 'like', '%' . $this->mostrarSerieDocumento() . '%')
            ->orderBy('id', 'DESC')->limit(1)->first();
    }


}
