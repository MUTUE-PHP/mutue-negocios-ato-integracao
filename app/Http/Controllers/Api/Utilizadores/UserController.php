<?php

namespace App\Http\Controllers\Api\Utilizadores;

use App\Http\Controllers\Controller;
use App\Jobs\JobRecuperacaoSenha;
use App\Mail\MailRecuperacaoSenhaMobile;
use App\Repositories\Empresa\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Keygen\Keygen;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function quantidadeUtilizadores()
    {
        return $this->userRepository->quantidadeUsers();

    }
    public function envioEmailRecuperarSenha(Request $request){
        $messages = [
            'email.required' => 'Informe o email',
        ];
        $validator = Validator::make($request->all(), [
            'email' => ["required", function($attr, $email, $fail){
                $user = DB::connection()->table('users_cliente')
                    ->where('email', $email)
                    ->first();
                if(!$user){
                    return response()->json([
                        'data' => null,
                        'message' => 'E-mail não encontrado'
                    ], 401);
                }
            }],
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'data' => null,
                'message' => $validator->errors()->messages()
            ], 401);
        }
        $token  = bin2hex(random_bytes(30));
        $data['linkLogin'] = getenv('APP_URL')."acessoLink?token=".$token;
        $data['email'] = $request->email;
        try {
            Mail::send(new MailRecuperacaoSenhaMobile($data));
            DB::connection('mysql2')->table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token
            ]);
            return response()->json([
                'data' => null,
                'message'=> 'Acessa o email para recuperar a senha'
            ]);
        } catch (\Exception $ex) {
            Log::error($ex);
        }
    }
    public function recuperacaoDeSenha(Request $request){


        $tokenValido = DB::connection('mysql2')->table('password_resets')->where('token', $request->token)
            ->where('used', 'N')->first();

        if($tokenValido){

        }
    }
}
