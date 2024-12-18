<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\admin\Empresa;
use App\Models\empresa\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/empresa/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('guest')->except('logout');
    }




    /**
     * subscrita de metodo
     */
    public function login(Request $request)
    {
            return $this->loginEmpresa($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended('/admin/home');
    }
    public function loginAdmin(Request $request)
    {

        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        if (is_numeric($request->email)) {
            $credentials = ['telefone' => $request->email, 'password' => $request->password];
        } else {
            $credentials = ['email' => $request->email, 'password' => $request->password];
        }

        if (auth()->guard('web')->attempt($credentials)) {
            return redirect('admin/home');
        } else {
            return $this->sendFailedLoginResponse($request);
        }
    }
    public function loginEmpresa(Request $request)
    {
        $request->validate([
            $this->username() => ["required", function ($attr, $value, $fail) {

                $userVendaOnline = DB::connection('mysql2')->table('users_cliente')
                    ->where('empresa_id', null)
                    ->where('email', $value)
                    ->first();

                if($userVendaOnline){
                    $fail("O cliente mutue vendas online não acessa o sistema");
                }
                $user = DB::connection('mysql2')->table('users_cliente')
                    ->where('telefone', $value)
                    ->orWhere('email', $value)
                    ->orWhere('username', $value)
                    ->first();

                if ($user) {
                    if ($user->status_id == 2 || $user->statusUserAdicional == 3) {
                        $fail("Utilizador sem acesso, está Inativo");
                    }
                }
            }],
            'password' => 'required|string',
        ]);



        if (is_numeric($request->email)) {
            $credentials = ['telefone' => $request->email, 'password' => $request->password];
        } else if ($this->isMail($request->get('email'))) {
            $credentials = ['email' => $request->email, 'password' => $request->password];
        } else {
            $credentials = ['username' => $request->email, 'password' => $request->password];
        }

        if (auth()->guard('empresa')->attempt($credentials)) {

            return redirect('empresa/home');
        } else {
            return $this->sendFailedLoginResponse($request);
        }
    }
    /**
     * subscrita de metodo
     */
    protected function attemptLogin(Request $request)
    {

        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    public function loginForm()
    {
        return view('admin.auth.login');
    }

    //Sobrescrevendo o método para logar com telefone
    protected function credentials(Request $request)
    {
        if (is_numeric($request->get('email'))) {
            return ['telefone' => $request->get('email'), 'password' => $request->get('password')];
        }


        return $request->only($this->username(), 'password');
    }
    public function isMail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    public function username()
    {
        return 'email';
    }

    public function showLoginForm()
    {
        $data['licencas'] = DB::connection('mysql')->table('licencas')->get();
        return view('welcome', $data);
    }
}
