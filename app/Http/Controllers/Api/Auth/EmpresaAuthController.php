<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\empresa\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmpresaAuthController extends Controller
{
    public function auth(Request $request)
    {
        $mensagem =  [
            'email.required' => 'E-mail/ou telefone é obrigatorio',
            'password.required' => 'Senha é obrigatorio',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|min:3|max:255',
            'password' => 'required|max:15'
        ], $mensagem);
        if ($validator->fails()) {
            return response()->json($validator->errors()->messages(), 401);
        }

        $user = User::with("perfis")->where('email', $request->email)
            ->orwhere('telefone', $request->email)->first();

        if ($user)
            $user->tokens()->delete();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciais invalidos'], 401);
        }

        if(!$user->token_notification_firebase && isset($request['tokenNotificationFirebase'])){
            User::where('id', $user->id)->update([
                'token_notification_firebase' => $request['tokenNotificationFirebase']
            ]);
        }
        $centros_custos = DB::connection("mysql2")->table("centro_custos")->where("empresa_id",$user->empresa_id)->get();
        $token = $user->createToken('mobile')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'uuid' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
                'telefone' => $user->telefone,
                'status_senha_id' => $user->status_senha_id,
                'foto' => $user->foto,
                'centrosCusto' => $this->isAdmin($user->perfis) ? $centros_custos : $user->centrosCusto
            ]
        ]);
    }
    public function getEmpresa()
    {
        return auth()->user()->empresa;
    }
    public function me()
    {
        $user = auth()->user();
        return response()->json($user, 200);
        // return new UserResource($user);
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'logout feito com sucesso'
        ]);
    }

    private function isAdmin($array)
    {
        $encontrado = false;
        foreach ($array as $valor) {
            if ($valor->id === 1) {
                $encontrado = true;
                break;
            }
        }
        return $encontrado;
    }
}
