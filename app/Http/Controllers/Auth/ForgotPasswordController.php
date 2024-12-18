<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;  // Importe o facade de Mail
use App\Mail\CustomResetPasswordMail; // Importe a sua classe Mailable personalizada

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;


    /*
    Metodo subscrito
    */
    public function broker($params =  null)
    {
        $params = $params ? $params : "";
        return Password::broker($params);
    }

    /*
    Metodo subscrito
    */
    public function sendResetLinkEmail(Request $request)
    {


        $this->validateEmail($request);
        $credentials = $this->credentials($request);
        $user1 = DB::connection('mysql')->table("users_admin")->where("email", $request->get("email"))->first();
        $user2 = DB::connection('mysql2')->table("users_cliente")->where("email", $request->get("email"))->first();


        if (!$user1 && !$user2) {
            return $this->sendResetLinkFailedResponse($request, Password::INVALID_USER);
        }

        if($user1){
            $user = $this->broker('users')->getUser($credentials);
            $token = $this->broker()->createToken($user);
            $email = $user['email'];
            $empresa = env('APP_NAME');
        }else{
            $user = $this->broker('empresas')->getUser($credentials);
            $token = $this->broker('empresas')->createToken($user);
            $email = $user['email'];
            $empresa = env('APP_NAME');
        }
        // Gere um token de redefinição de senha para o usuário
        $data['token'] = $token;
        $data['empresa'] = $empresa;
        $data['email'] = $email;

        // Envie o e-mail personalizado usando a classe Mailable
        Mail::to($email)->send(new CustomResetPasswordMail($data));
        return $this->sendResetLinkResponse($request, Password::RESET_LINK_SENT);

    }
    protected function validateEmail(Request $request)
    {
        $request->validate(
            [
                'email' => ['required', function ($attr, $email, $fail) use ($request) {
                    $connection2 = DB::connection('mysql2')->table("users_cliente")->where("email", $request->get("email"))->first();
                    if (!$connection2) {
                        $fail('E-mail não encontrado');
                    }
                }]
            ]
        );
    }
}
