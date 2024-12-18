<?php

namespace App\Http\Controllers\VM\SubscricaoVendaOnline;

use App\Application\UseCase\VendasOnline\SubscricaoVendaOnline\DesativarSubscricaoVendaOnline;
use App\Application\UseCase\VendasOnline\SubscricaoVendaOnline\SubscricaoVendaOnline;
use App\Http\Controllers\Controller;
use App\Infra\Factory\VendasOnline\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubscricaoVendaOnlineCreateController extends Controller
{
    public function create(Request $request)
    {
        $messages = [
            'email.required' => 'Informe o email',
        ];
        $validator = Validator::make($request->all(), [
            'email' => ["required", function ($attr, $email, $fail) {
                $emailSubscrito = DB::table('subscricao_emails')
                    ->where('email', $email)
                    ->where('estado_recebimento', 'ACTIVO')
                    ->first();

                if ($emailSubscrito) {
                    $fail("E-mail " . $email . " já subscrito");
                }
            }],
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors()->messages(), 400);
        }
        $subscricao = new SubscricaoVendaOnline(new DatabaseRepositoryFactory());
        $subscricao = $subscricao->execute($request->email);
        if($subscricao){
            return response()->json([
                'data' => $subscricao,
                'message' => 'Operação realizada com sucesso'
            ]);
        }
    }
    public function deleted(Request $request){

        $messages = [
            'email.required' => 'Informe o email',
        ];
        $validator = Validator::make($request->all(), [
            'email' => ["required", function ($attr, $email, $fail) {
                $emailSubscrito = DB::table('subscricao_emails')
                    ->where('email', $email)
                    ->first();
                if (!$emailSubscrito) {
                    $fail("E-mail " . $email . " não está subscrito");
                }
            }],
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors()->messages(), 400);
        }
        $subscricao = new DesativarSubscricaoVendaOnline(new DatabaseRepositoryFactory());
        $subscricao = $subscricao->execute($request->email);
        if($subscricao){
            return response()->json([
                'data' => $subscricao,
                'message' => 'Operação realizada com sucesso'
            ]);
        }
    }

}
