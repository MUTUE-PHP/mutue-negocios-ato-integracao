<?php

namespace App\Http\Controllers\empresa\CartaoCliente;

use App\Application\UseCase\Empresa\CartaoCliente\AtualizarCartaoCliente;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartaoClienteUpdateController
{

    public function atualizarCartaoCliente(Request $request)
    {
        $message = [
            'clienteId.required' => 'Informe o cliente',
            'dataEmissao.required' => 'Informe a data de emissão',
            'dataValidade.required' => 'Informe a data de válidade'
        ];
        $validator = Validator::make($request->all(), [
            'clienteId' => ['required'],
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
        $emitirCartaoCliente = new AtualizarCartaoCliente(new DatabaseRepositoryFactory());
        $cartaoCliente = $emitirCartaoCliente->execute($request);
        return response()->json([
            'data' => $cartaoCliente,
            'message' => 'Operação realizada com sucesso'
        ], 200);

    }

}
