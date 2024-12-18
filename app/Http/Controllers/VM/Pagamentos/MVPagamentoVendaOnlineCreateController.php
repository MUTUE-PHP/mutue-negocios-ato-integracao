<?php

namespace App\Http\Controllers\VM\Pagamentos;

use App\Application\UseCase\Empresa\Armazens\GetArmazens;
use App\Application\UseCase\Empresa\CartaoCliente\IsValidoCartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\VerificarSaldoSuficienteDescontarCartao;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Application\UseCase\VendasOnline\Clientes\GetClientePeloUserId;
use App\Application\UseCase\VendasOnline\PagamentoCompras\AtualizarHistoricoPagamentoOnline;
use App\Application\UseCase\VendasOnline\PagamentoCompras\AtualizarPagamento;
use App\Application\UseCase\VendasOnline\PagamentoCompras\EnviarPagamentoCompraVendaOnline;
use App\Application\UseCase\VendasOnline\PagamentoCompras\GetTotalPagarCarrinho;
use App\Http\Controllers\Controller;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use App\Infra\Repository\VendasOnline\RelatorioVendaOnlineJasper;
use App\Mail\NotificacaoPagamentoVendaOnline;
use App\Mail\NotificacaoReinvioPagamentoVendaOnline;
use App\Models\admin\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MVPagamentoVendaOnlineCreateController extends Controller
{
    public function reinviarComprovativoPagamento(Request $request)
    {
        $message = [
            'pagamentoId.required' => 'Informe o código do pagamento reinviado',
            'comprovativoBancario.required' => 'Adiciona o comprovativo bancário',
            'comprovativoBancario.mimes' => 'Formatos suportado comprovativo:(png, jpeg, pdf)'
        ];
        $pagamento = DB::connection('mysql2')->table('pagamentos_vendas_online')
            ->where('id', $request['pagamentoId'])->first();

        $validator = Validator::make($request->all(), [
            'pagamentoId' => ['required', function ($attr, $pagamentoId, $fail) use ($request, $pagamento) {
                if (!$pagamento) {
                    $fail("Pagamento não encontrado");
                }
            }],
            'comprovativoBancario' => 'required|image|mimes:jpeg,png, pdf'
        ], $message);

        if ($validator->fails()) {
            return response()->json($validator->errors()->messages(), 400);
        }


        try {
            $pagamento = (array)$pagamento;

            $comprovativoBancarioAnterior = $pagamento['comprovativoBancario'];
            $this->eliminarComprovativoAnterior($comprovativoBancarioAnterior);

            $comprovativoBancario = $this->getNomeComprovativoBancario($request);

            $STATUSPENDENTE = 2;
            $atualizarPagamento = new AtualizarPagamento(new DatabaseRepositoryFactory());
            $atualizarPagamento->execute($request['pagamentoId'], $STATUSPENDENTE, $comprovativoBancario);


            $descricao = "O cliente " . $pagamento['nomeUserEntrega'] . " reinviou o comprovativo de pagamento";
            $atualizarHistoricoPagamento = new AtualizarHistoricoPagamentoOnline(new DatabaseRepositoryFactory());
            $atualizarHistoricoPagamento->execute($pagamento['id'], $STATUSPENDENTE, $descricao);

            $cliente = new GetClientePeloUserId(new DatabaseRepositoryFactory());
            $cliente = $cliente->execute(Auth::user()->id);

//
            $data['cliente'] = $pagamento['nomeUserEntrega'] ?? Auth::user()->name;
            $data['nifEmpresa'] = $cliente->nif;

            $data['enderecoEmpresa'] = $pagamento['enderecoEntrega'];
            $data['emails'] = $this->getEmailsAdminParaNotificar();

            $data['codigo'] = $pagamento['codigo'];
            array_push($data['emails'], $pagamento['emailEntrega']);

            $data['assunto'] = 'Reinvio do pagamento: ' . $pagamento['codigo'];
            $data['comprovativoBancario'] = env('APP_URL') . 'upload/' . $comprovativoBancario;

            try {
                Mail::send(new NotificacaoReinvioPagamentoVendaOnline($data));
            } catch (\Exception $ex) {
                Log::error($ex->getMessage());
            }

            return $pagamento;
        } catch (\Error $e) {
            Log::error($e->getMessage());
        }


    }

    private function getEmailsAdminParaNotificar()
    {
        return User::where('notificarAtivacaoLicenca', 'Y')
            ->pluck('email')->toArray();
    }

    public function eliminarComprovativoAnterior($comprovativoBancario)
    {
        if ($comprovativoBancario && Storage::exists($comprovativoBancario)) {
            Storage::delete($comprovativoBancario);
        }
    }

    private function getNomeComprovativoBancario($request)
    {
        $comprovativoBancario = $request['comprovativoBancario']->store('comprovativosVendasOnline');
        return $comprovativoBancario;
    }

    public function enviarPagamento(Request $request)
    {


        $getFinalizarPagamento = new GetParametroPeloLabelNoParametro(new \App\Infra\Factory\Empresa\DatabaseRepositoryFactory());
        $getFinalizarPagamento = $getFinalizarPagamento->execute('finalizar_pagamento');
//        if($getFinalizarPagamento->valor == 'não'){
//            return response()->json([
//                'data' => null,
//                'message' => 'Obrigado pela escolha. Mutue Loja ONLINE só estará aberta no dia 25 de Novembro de 2023'
//            ], 400);
//        }

        $numeroCartaoCliente = isset($request['numeroCartaoCliente']) ? $request['numeroCartaoCliente'] : null;
        $request['numeroCartaoCliente'] = $numeroCartaoCliente;
        $pagamento = json_decode($request->pagamento, true);


        $message = [
            'apelidoUserEntrega.required' => 'Informe o apelido',
            'bancoId.required' => 'Informe o banco',
            'comunaId.required' => 'Informe a comuna',
            'dataPagamentoBanco.required' => 'Informe a data de pagamento no banco',
            'formaPagamentoId.required' => 'Informe a forma de pagamento',
            'nomeUserEntrega.required' => 'Informe o titular',
            'telefoneUserEntrega.required' => 'Informe o contato',
            'provinciaIdEntrega.required' => 'Informe a província',
            'tipoEntregaId.required' => 'Informe o tipo de entrega'
        ];
        $validator = Validator::make($pagamento, [
            'dataPagamentoBanco' => ['required'],
            'formaPagamentoId' => ['required'],
            'bancoId' => ['required'],
            'nomeUserEntrega' => ['required'],
            'apelidoUserEntrega' => ['required'],
            'provinciaIdEntrega' => ['required'],
            'comunaId' => ['required'],
            'enderecoEntrega' => [function ($attr, $enderecoEntrega, $fail) use ($request) {
                if ($request->tipoEntregaId == 1 && !$enderecoEntrega) {
                    $fail("Informe o endereço de entrega");
                }
            }],
            'telefoneUserEntrega' => ['required'],
            'tipoEntregaId' => ['required'],
        ], $message);

        $comprovativoBancario = null;

        if ($request['numeroCartaoCliente'] && $request['numeroCartaoCliente'] != 'null') {
            $getCarrinho = new GetTotalPagarCarrinho(new DatabaseRepositoryFactory());
            $totalDescontar = $getCarrinho->execute();
            $getSaldoSuficiente = new VerificarSaldoSuficienteDescontarCartao(new \App\Infra\Factory\Empresa\DatabaseRepositoryFactory());
            $saldoSuficiente = $getSaldoSuficiente->execute($numeroCartaoCliente, $totalDescontar);

            $cartaoCliente = new IsValidoCartaoCliente(new \App\Infra\Factory\Empresa\DatabaseRepositoryFactory());
            $isValid = $cartaoCliente->execute($numeroCartaoCliente);

            if (!$isValid) {
                return response()->json('Cartão está expirado', 422);
            }
            if (!$saldoSuficiente) {
                return response()->json('Saldo insuficiente no cartão', 422);
            }
        } else {

            $comprovativoBancario = $request->file('comprovativoBancario');
            if (!$request->file('comprovativoBancario')->getClientOriginalName()) {
                return response()->json('Adicionar comprovativo bancário', 422);
            }
            if ($validator->fails()) {
                return response()->json($validator->errors()->messages(), 422);
            }
            $comprovativoFormato = $request->file('comprovativoBancario')->getClientOriginalExtension();
            $formatosSuportado = array("jpg", "jpeg", "png", "pdf");
            if (!in_array($comprovativoFormato, $formatosSuportado)) {
                return response()->json('Formato do comprovativo bancário não suportado', 422);
            }
        }
        try {
            DB::beginTransaction();
            $pagamento['comprovativoBancario'] = $comprovativoBancario;
            $pagamento['numeroCartaoCliente'] = $request['numeroCartaoCliente'] ? $request['numeroCartaoCliente'] : null;
            $pagamentoCompra = new EnviarPagamentoCompraVendaOnline(new DatabaseRepositoryFactory());
            $output = $pagamentoCompra->execute($pagamento);
            DB::commit();
            return response()->json([
                'data' => [
                    'url' => env('APP_URL') . 'api/portal/pagamentosVendaOnline/imprimir/' . $output->id,
                ],
                'message' => "Operação realizada com sucesso! Código: $output->codigo"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'data' => null,
                'message' => $e->getMessage()
            ]);
        }
    }
}
