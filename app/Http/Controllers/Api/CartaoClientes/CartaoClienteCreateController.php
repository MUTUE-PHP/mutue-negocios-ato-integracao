<?php

namespace App\Http\Controllers\Api\CartaoClientes;

use App\Application\UseCase\Empresa\CartaoCliente\CadastrarCartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\GetCartaoClientePeloNumero;
use App\Application\UseCase\Empresa\CartaoCliente\IsValidoCartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\VerificarSaldoSuficienteDescontarCartao;
use App\Http\Controllers\Controller;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartaoClienteCreateController extends Controller
{

    public function emitirCartaoCliente(Request $request){

        $message = [
            'clienteId.required' => 'Informe o cliente',
            'dataEmissao.required' => 'Informe a data de emissão',
            'dataValidade.required' => 'Informe a data de válidade'
        ];
        $validator = Validator::make($request->all(), [
            'clienteId' => ['required', function($attr, $clienteId, $fail) use($request){
                $cliente = DB::connection('mysql2')->table('cartao_clientes')
                    ->where('clienteId', $request->clienteId)
                    ->where('empresa_id', auth()->user()->empresa_id)->first();
                if($cliente) {
                    return response()->json([
                        'data' => null,
                        'message' => 'O cliente já tem cartão'
                    ]);
                }
            }],
            'dataEmissao' => ['required'],
            'dataValidade' => ['required', function ($attr, $dataValidade, $fail) use ($request) {
                if ($request->dataEmissao > $dataValidade) {
                    $fail('Data emissão deve ser menor a data de válidade');
                }
            }],
        ], $message);
        if ($validator->fails()) {
            return response()->json($validator->errors()->messages(), 422);
        }
        $emitirCartaoCliente = new CadastrarCartaoCliente(new DatabaseRepositoryFactory());
        $cartaoCliente = $emitirCartaoCliente->execute($request);

        return response()->json([
            'data' => $cartaoCliente,
            'message' => 'Operação realizada com sucesso'
        ], 200);
    }
    public function cartaoClienteValido($numeroCartao){
        $cartaoCliente = new IsValidoCartaoCliente(new DatabaseRepositoryFactory());
        $isValid = $cartaoCliente->execute($numeroCartao);
        $message = 'Número do cartão inválido';
        if($isValid){
            $message = 'Cartão válido';
        }
       return response()->json([
           'data' => $isValid,
           'message' => $message
       ]);
    }
    public function cartaoClienteSaldoSuficienteDescontar(Request $request, $numeroCartaoCliente){
        $getSaldoSuficiente = new VerificarSaldoSuficienteDescontarCartao(new DatabaseRepositoryFactory());
        $isValid = $getSaldoSuficiente->execute($numeroCartaoCliente, $request->valorDescontar);
        $message = "saldo insuficiente";
        if($isValid){
            $message = 'Saldo suficiente';
        }
        return response()->json([
            'data' => $isValid,
            'message' => $message
        ]);
    }

}
