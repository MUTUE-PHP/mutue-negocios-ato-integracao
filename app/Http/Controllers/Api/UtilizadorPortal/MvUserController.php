<?php

namespace App\Http\Controllers\Api\UtilizadorPortal;

use App\Http\Controllers\Controller;
use App\Mail\AtivacaoCadastrarUsuarioOnline;
use App\Mail\EnviarCodigoRecuperacaoSenhaVendaOnline;
use App\Models\empresa\Cliente;
use App\Models\empresa\User;
use App\Repositories\Empresa\UserPortalRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Keygen\Keygen;

class MvUserController extends Controller
{

    private $userRepository;

    public function __construct(UserPortalRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function quantidadeUtilizadores()
    {
        return $this->userRepository->quantidadeUsers();
    }

    public function novaSenhaMv(Request $request)
    {
        $mensagem = [
            'codigo.required' => 'Informe o código',
            'password1.required' => 'Informe a senha',
            'password2.required' => 'Informe confirmar a senha',
        ];

        $dataValicacao = DB::connection('mysql2')->table('validacao_user_recuperar_senha')
            ->where('codigo', $request->codigo)
            ->where('used', 'N')
            ->first();
        if (!$dataValicacao) {
            return response()->json([
                'data' => null,
                'message' => 'Código não encontrado/ou usado'
            ], 400);
        }

        if ($request->password1 !== $request->password2) {
            return response()->json([
                'data' => null,
                'message' => 'As senhas não correspondem'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'codigo' => ['required'],
            'password1' => ['required'],
            'password2' => ['required'],
        ], $mensagem);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'data' => null,
                'message' => $errors->all()[0]
            ], 400);
        }
        DB::connection('mysql2')->table('users_cliente')
            ->where('id', $dataValicacao->userId)
            ->where('tipo_user_id', 4)
            ->update([
                'password' => Hash::make($request->password1),
                'updated_at' => Carbon::now()
            ]);
        DB::connection('mysql2')->table('validacao_user_recuperar_senha')
            ->where('userId', $dataValicacao->userId)
            ->where('codigo', $dataValicacao->codigo)->update([
                'used' => 'Y'
            ]);

        return response()->json([
            'data' => null,
            'message' => 'Operação realizada com sucesso!'
        ]);

    }

    public function recuperarSenhaMV(Request $request)
    {

        $mensagem = [
            'email.required' => 'Informe o email',
        ];
        $user = User::where('email', $request->email)
            ->where('tipo_user_id', 4)->first();
        $validator = Validator::make($request->all(), [
            'email' => ['required', function ($attr, $email, $fail) use ($user) {

                if (!$user) {
                    $fail("E-mail não encontrado");
                }
            }]
        ], $mensagem);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'data' => null,
                'message' => $errors->all()[0]
            ], 400);
        }
        $codigo = mb_strtoupper(Keygen::alphanum(6)->generate());
        $data['email'] = $request['email'];
        $data['codigo'] = $codigo;

        DB::connection('mysql2')->table('validacao_user_recuperar_senha')->insert([
            'userId' => $user->id,
            'codigo' => $codigo,
            'used' => 'N'
        ]);

        try {
            Mail::send(new EnviarCodigoRecuperacaoSenhaVendaOnline($data));
            return response()->json([
                'data' => null,
                'message' => 'Operação realizada com sucesso! Acessa o seu email para recuperar a senha'
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function createNewUserSendCodigo(Request $request)
    {
        $mensagem = [
            'name.required' => 'Informe o nome',
            'email.required' => 'Informe o E-mail',
            'password1.required' => 'Informe a senha',
            'password2.required' => 'Informe novamente a senha',
            'telefone.required' => 'Informe o telefone'
        ];
        $validator = Validator::make($request->all(), [
            'email' => ['required', function ($attr, $email, $fail) use ($request) {
                $user = User::where('email', $request->email)->first();
                if ($user) {
                    $fail("E-mail já cadastrado. Recupera a senha");
                }
            }],
            'name' => 'required',
            'password1' => ["required", function ($attr, $password1, $fail) use ($request) {
                if ($password1 !== $request->password2) {
                    $fail("As senhas não correspondem");
                }
            }],
            'password2' => ["required"],
            'telefone' => ["required", function ($attr, $telefone, $fail) {
                $user = User::where('telefone', $telefone)
                    ->where('tipo_user_id', 4)->first();
                if ($user) {
                    $fail("Telefone já cadastrado");
                }
            }]
        ], $mensagem);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'data' => null,
                'message' => $errors->all()
            ], 400);
        }

        $codigo = mb_strtoupper(Keygen::alphanum(6)->generate());
        $data['email'] = $request['email'];
        $data['codigo'] = $codigo;
        $request['codigo'] = $codigo;
        $this->userRepository->createValidacaoUser($request);

       try {
            Mail::send(new AtivacaoCadastrarUsuarioOnline($data));
            return response()->json([
                'data' => null,
                'message' => 'Operação realizada com sucesso! Acessa o seu email para válidar a conta'
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
//            dd($e->getMessage());
            return response()->json([
                'data' => null,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    public function isCodigoCreateUser($request){
        return DB::connection('mysql2')->table('users_cliente_validacao')
            ->where('codigo', $request->codigo)
            ->where('email', $request['email'])
            ->orderBy('id', 'DESC')
            ->first();
    }
    public function isUsedCreateUser($request){
        return DB::connection('mysql2')->table('users_cliente_validacao')
            ->where('used', 'Y')
            ->where('email', $request['email'])
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function getCodigoCreateUser($request)
    {
        return DB::connection('mysql2')->table('users_cliente_validacao')
            ->where('codigo', $request->codigo)
            ->where('email', $request->email)
            ->where('used', 'N')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function updateUserByUsed($request)
    {
        DB::connection('mysql2')->table('users_cliente_validacao')
            ->where('codigo', $request->codigo)
            ->where('email', $request->email)
            ->update([
                'used' => 'Y'
            ]);
    }

    public function createNewUserMobile(Request $request)
    {
        $mensagem = [
            'name.required' => 'Informe o nome',
            'email.required' => 'Informe o E-mail',
        ];
        $validator = Validator::make($request->all(), [
            'email' => ['required'],
            'name' => 'required'
        ], $mensagem);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'data' => null,
                'message' => $errors->all()[0]
            ], 400);
        }
        $user= DB::connection('mysql2')->table('users_cliente')
            ->where('email', $request['email'])
            ->first();

        if(!$user){
            $user = $this->userRepository->createNewUserMobile($request);
        }

        $cliente = Cliente::with(['cartaoCliente'])->where('user_id', $user->id)
            ->where('empresa_id', $user->empresa_id)
            ->first();

        $cartaoCliente = DB::connection('mysql2')->table('cartao_clientes')
            ->where('clienteId', $cliente->id)
            ->first();

        if ($user){
            $user->tokens()->delete();
        }
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => (string)$user->id,
                'uuid' => $user->uuid,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'telefone' => $user->telefone,
                'endereco' => isset($cliente->endereco) ? $cliente->endereco : null,
                'nif' => isset($cliente->nif) ? $cliente->nif : null,
                'cidade' => isset($cliente->cidade) ? $cliente->cidade : null,
                'status_senha_id' => $user->status_senha_id,
                'foto' => $user->foto,
                'centrosCusto' => $user->centrosCusto,
                'cartaoCliente' => $cartaoCliente ?? null
            ]
        ]);
    }
    public function createNewUser(Request $request)
    {
        $mensagem = [
            'codigo.required' => 'Informe o código',
            'email.required' => 'Informe o email',
        ];
        $validator = Validator::make($request->all(), [
            'codigo' => ['required'],
            'email' => ['required', function($attr, $email, $fail){
                $emailExiste =  DB::connection('mysql2')->table('users_cliente')
                    ->where('email', $email)->first();
                if($emailExiste){
                    $fail("E-mail já cadastrado. Recupera a senha");
                }
            }],
        ], $mensagem);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'data' => null,
                'message' => $errors->all()[0]
            ], 400);
        }
        if(!$this->getCodigoCreateUser($request)){
            return response()->json([
                'data' => null,
                'message' => 'Código não encontrado'
            ], 400);
        }
        if($this->isUsedCreateUser($request)){
            return response()->json([
                'data' => null,
                'message' => 'Código já foi utilizado'
            ], 400);
        }
        $requestData = (array) $this->getCodigoCreateUser($request);
        $user = $this->userRepository->createNewUser($requestData);
        $this->updateUserByUsed($request);
        $output = [
            'uuid' => $user->uuid,
            'name' => $user->name,
            'foto' => $user->foto,
            'email' => $user->email,
            'password' => $request->password1,
        ];
        return response()->json([
            'data' => $output,
            'message' => 'Usuário cadastro com sucesso!'
        ]);
    }
    public function updateUser(Request $request, $uuid)
    {
      

        $user = json_decode($request->user, true);
        $mensagem =  [
            'name.required' => 'Informe o nome',
            'useername' => 'Informe o username',
            'email.required' => 'Informe o E-mail',
            'telefone.required' => 'Informe o telefone'
        ];

        $validator = Validator::make($data, [
            'email' => ['required', function ($attr, $email, $fail) use ($uuid) {
                $user = User::where('uuid', '!=', auth()->user()->uuid)
                    ->where('email', $email)
                    ->where('empresa_id', auth()->user()->empresa_id)
                    ->first();
                if ($user) {
                    $fail("E-mail já cadastrado");
                }
            }],
            'password1' => [function ($attr, $password1, $fail) use ($data) {
                if ($password1 && ($password1 !== $data['password2'])) {
                    $fail("As senhas não correspondem");
                }
            }],
            'name' => 'required',
            'username' => 'required',
            'telefone' => ["required", function ($attr, $telefone, $fail) use ($uuid) {
                $user = User::where('uuid', '!=', auth()->user()->uuid)
                    ->where('telefone', $telefone)
                    ->where('empresa_id', auth()->user()->empresa_id)
                    ->first();
                if ($user) {
                    $fail("Telefone já cadastrado");
                }
            }]
        ], $mensagem);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'data' => null,
                'message' => $errors->all()[0]
            ], 401);
        }
        $user = $this->userRepository->updateUser($data, $uuid);
        $cliente = Cliente::where('user_id', $user->id)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->first();

        return response()->json([
            'data' => [
                'id' => (string)$user->id,
                'uuid' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'telefone' => $user->telefone,
                'endereco' => isset($cliente->endereco) ? $cliente->endereco:null,
                'nif' => isset($cliente->nif)?$cliente->nif:null,
                'cidade' => isset($cliente->cidade)?$cliente->cidade:null,
                'foto' => $user->foto,
            ],
            'message' => 'Atualizado o dados do utilizador'
        ]);
    }

    public function desativarConta(Request $request) {
   
        $request->validate([
            'password.required' => 'Insere a Senha',
           
        ]);

        $user = User::where('uuid',auth()->user()->uuid)->first();
        if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'message' => 'Senha incorreta',
            ], 401);
        }

        $user->update(['status_id' => 2]);
        return response()->json([
            'message' =>'Conta Desativada',
        ]);
    }
    
       
}
