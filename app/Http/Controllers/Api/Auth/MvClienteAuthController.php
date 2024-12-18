<?php

namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;
use App\Models\empresa\Cliente;
use App\Models\empresa\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MvClienteAuthController extends Controller
{
    public function auth(Request $request)
    {
        $mensagem =  [
            'email.required' => 'E-mail/ou telefone é obrigatorio',
            'password.required' => 'Senha é obrigatorio',
        ];
        $user = User::where('email', $request->email)
            ->orwhere('telefone', $request->email)->first();
        if (!$user) {
            return response()->json([
                'data' => null,
                'message' => 'O usuário não encontrado'
            ], 400);
        }
        $cliente = Cliente::with(['cartaoCliente'])->where('user_id', $user->id)
            ->where('empresa_id', 148)
            ->first();

        if(!$cliente){
            return response()->json([
                'data' => null,
                'message' => 'Usuário não encontrado'
            ], 400);

        }


        if ($user->status_id == 2) {
            return response()->json([
                'data' => null,
                'message' => 'Conta desativada'
            ], 400);
        }


        $validator = Validator::make($request->all(), [
            'email' => ['required'],
            'password' => 'required'
        ], $mensagem);
        if ($validator->fails()) {
            return response()->json($validator->errors()->messages(), 401);
        }
        if(!$user->token_notification_firebase && isset($request['tokenNotificationFirebase'])){
            User::where('id', $user->id)->update([
                'token_notification_firebase' => $request['tokenNotificationFirebase']
            ]);
        }
        $user = User::with('cliente', 'centrosCusto')->where('email', $request->email)
            ->orwhere('telefone', $request->email)
            ->first();
        $cliente = Cliente::with(['cartaoCliente'])->where('user_id', $user->id)
        ->where('empresa_id', 148)
        ->first();
        $cartaoCliente = DB::connection('mysql2')->table('cartao_clientes')
            ->where('clienteId', $cliente->id)
            ->first();
        if ($user)
            $user->tokens()->delete();



        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciais invalidos'], 401);
        }

        if ($cliente->status_id == 2) {

            return response()->json([
                'data' => null,
                'message' => 'Conta desativada'
            ], 400);
        }

        $token = $user->createToken('mobile')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => (string)$user->id,
                'uuid' => $user->uuid,
                'name' => $user->name,
                'username' =>$user->username,
                'email' => $user->email,
                'telefone' => $user->telefone,
                'endereco' => isset($cliente->endereco) ? $cliente->endereco:null,
                'nif' => isset($cliente->nif)?$cliente->nif:null,
                'cidade' => isset($cliente->cidade)?$cliente->cidade:null,
                'status_senha_id' => $user->status_senha_id,
                'foto' => $user->foto,
                'centrosCusto' => $user->centrosCusto,
                'cartaoCliente' => $cartaoCliente??null
            ]
        ]);
    }
    public function getUser(Request $request){
        $mensagem =  [
            'email.required' => 'Informe o email',
        ];

        $validator = Validator::make($request->all(), [
            'email' => ['required', function ($attr, $email, $fail) use ($request) {
                $user = User::where('email', $request->email)->first();
                if (!$user) {
                    $fail("O usuário não encontrado");
                }
            }],
        ], $mensagem);
        if ($validator->fails()) {
            return response()->json($validator->errors()->messages(), 401);
        }
        $user = User::with('cliente', 'centrosCusto')->where('email', $request->email)
            ->where('tipo_user_id', 4)->first();

        $cliente = Cliente::with(['cartaoCliente'])->where('user_id', $user->id)
            ->where('empresa_id', $user->empresa_id)
            ->first();

        $cartaoCliente = DB::connection('mysql2')->table('cartao_clientes')
            ->where('clienteId', $cliente->id)
            ->first();

        if ($user)
            $user->tokens()->delete();
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 401);
        }
        $token = $user->createToken('mobile')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => (string)$user->id,
                'uuid' => $user->uuid,
                'name' => $user->name,
                'username' =>$user->username,
                'email' => $user->email,
                'telefone' => $user->telefone,
                'endereco' => isset($cliente->endereco) ? $cliente->endereco:null,
                'nif' => isset($cliente->nif)?$cliente->nif:null,
                'cidade' => isset($cliente->cidade)?$cliente->cidade:null,
                'status_senha_id' => $user->status_senha_id,
                'foto' => $user->foto,
                'centrosCusto' => $user->centrosCusto,
                'cartaoCliente' => $cartaoCliente??null
            ]
        ]);
    }
    public function getEmpresa()
    {
        return auth()->user()->empresa;
    }
    public function me()
    {
       $user = User::with('cliente')->where('id', auth()->user()->id)
            ->first();
        $cartaoCliente = DB::connection('mysql2')->table('cartao_clientes')
            ->where('clienteId', $user->cliente->id)
            ->first();
       $user['cartaoCliente'] = $cartaoCliente??null;
        return response()->json($user, 200);
        // return new UserResource($user);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'logout feito com sucesso'
        ]);
    }
}
