<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;




class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $connection1;
    protected $connection2;



    /*
        subscrito o metodo
    */

    public function reset(Request $request)
    {


        $request->validate($this->rules(), $this->validationErrorMessages());
        $this->connection1 = DB::connection('mysql')->table("users_admin")->where("email", $request->get("email"))->first();
        $this->connection2 = DB::connection('mysql2')->table("users_cliente")->where("email", $request->get("email"))->first();

        if ($this->connection1) {
            $response = $this->broker("users")->reset($this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            });
            return $response == Password::PASSWORD_RESET ? $this->sendResetResponse($request, $response) : $this->sendResetFailedResponse($request, $response);
        }
        if ($this->connection2) {
            $response = $this->broker("empresas")->reset($this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            });

            return $response == Password::PASSWORD_RESET ? $this->sendResetResponse($request, $response) : $this->sendResetFailedResponse($request, $response);
        }

        if (!$this->connection1 && !$this->connection2) {


            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
            $response = $this->broker()->reset(
                $this->credentials($request),
                function ($user, $password) {
                    $this->resetPassword($user, $password);
                }
            );

            return $response == Password::PASSWORD_RESET ? $this->sendResetResponse($request, $response) : $this->sendResetFailedResponse($request, $response);
        }

        // dd(Password::PASSWORD_RESET);
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.


        //return $response == Password::PASSWORD_RESET ? $this->sendResetResponse($request, $response) : $this->sendResetFailedResponse($request, $response);
    }

    /*
    subscrita de metodo
    */

    /*
    Metodo subscrito
    */
    public function broker($params =  null)
    {
        $params = $params ? $params : "";
        return Password::broker($params);
    }

    protected function guard()
    {
        if ($this->connection1) {
            $connection = "web";
        }
        if ($this->connection2) {
            $connection = "empresa";
        }
        return Auth::guard($connection);
    }



    public function validationErrorMessages(){
        return  [
            'password.required' => 'campo obrigatório',
            'password_confirmation.required' => 'campo obrigatório',
            'password.confirmed' => 'confirmação da senha não corresponde',
            'password.min' => 'senha deve ter pelo menos 8 caracteres',
        ];
    }

    /*
    subscrita de metodo
    */

    protected function sendResetResponse(Request $request, $response)
    {

        if ($this->connection1) {
            $redirect = "/home";
        }
        if ($this->connection2) {
            $redirect = "/empresa/home";
        }
        return redirect($redirect)
            ->with('status', trans($response));
    }
}
