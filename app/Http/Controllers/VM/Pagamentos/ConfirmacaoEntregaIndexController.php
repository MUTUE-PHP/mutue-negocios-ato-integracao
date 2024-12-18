<?php

namespace App\Http\Controllers\VM\Pagamentos;
use App\Application\UseCase\VendasOnline\PagamentoCompras\ConfirmarEntrega;
use App\Http\Controllers\Controller;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfirmacaoEntregaIndexController extends Controller
{
    public function confirmarEntrega(Request $request){

        $message = [
            'pagamentoId.required' => 'Informe o pagamento'
        ];
        $validator = Validator::make($request->all(), [
            'pagamentoId' => ['required']
        ], $message);
        if ($validator->fails()) {
            return response()->json($validator->errors()->messages(), 422);
        }
        $confirmarEntrega = new ConfirmarEntrega(new DatabaseRepositoryFactory());
        $outputConfirmarEntrega = $confirmarEntrega->execute($request->pagamentoId);
        if($outputConfirmarEntrega){
            return response()->json([
                'data' => $outputConfirmarEntrega,
                'message' => 'Operação realizada com sucesso'
            ], 200);
        }
    }
}
