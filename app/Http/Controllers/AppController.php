<?php

namespace App\Http\Controllers;

use App\Events\EnvioPagamentoVendaOnline;
use App\Models\empresa\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppController extends Controller {

    use TraitFirebaseService;

    public function __construct()
    {
      //  dd(auth()->guard('empresa')->user());
    }


    public function logout(){
        Auth::guard('web')->logout();
        Auth::guard('empresa')->logout();

    }

    public function Home() {

        $this->logout();
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = $_SERVER['REQUEST_URI'];
            $repartiUrl = explode('=', $url);
            if ($repartiUrl[0] == '/login?email') {
                $emailcod = $repartiUrl[1];
                $email = base64_decode(base64_decode(base64_decode($emailcod)));
                $user = User::where('email', $email)->first();
                if ($user) {
                    Auth::guard('empresa')->login($user);
                    return redirect('/empresa/home');
                } else {
                    return view('login');
                }
            } else {
                return view('login');
            }
            return view('login');
        }
        $data['licencas'] = DB::connection('mysql')->table('licencas')
        ->join('tipotaxa', 'tipotaxa.codigo', '=', 'licencas.tipo_taxa_id')
        ->get();
        broadcast(new EnvioPagamentoVendaOnline('some data'));
        return view('login', $data);
    }

}
