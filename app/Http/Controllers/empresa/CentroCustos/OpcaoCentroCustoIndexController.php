<?php

namespace App\Http\Controllers\empresa\CentroCustos;

use App\Application\UseCase\Empresa\CentrosDeCusto\GetCentrosCusto;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\CentroCusto;
use App\Models\empresa\User;
use App\Models\empresa\UserPerfil;
use Livewire\Component;
use Illuminate\Http\Request;

class OpcaoCentroCustoIndexController extends Component
{

    public function index()
    {
        $centroCustos = CentroCusto::where('empresa_id', auth()->user()->empresa_id)->get();
        session()->put('centroCustoId', $centroCustos[0]['id']);
        session()->put('centroCustoNome', $centroCustos[0]['nome']);
        if (session()->has('centroCustoId')) {
            return redirect("empresa/home");
        }
//        dd($centroCustos);
//        $userAdmin = UserPerfil::where('user_id', auth()->user()->id)
//            ->where('perfil_id', 1)->first();
//        if($userAdmin){
//            $centroCustos = CentroCusto::where('empresa_id', auth()->user()->empresa_id)->get();
//            $data['centrosCusto'] = $centroCustos;
//        }else{
//            $user = User::with(['centrosCusto', 'perfis'])->find(auth()->user()->id);
//            $data['centrosCusto'] = $user->centrosCusto;
//        }
//
//        return view('empresa.centroCustos.opcaoCentrosCusto', $data);
    }

    public function selecionarCentroCusto(Request $request, $centroCusto)
    {
        $request->session()->put('centroCustoId', $centroCusto['id']);
        $request->session()->put('centroCustoNome', $centroCusto['nome']);
        if (session()->has('centroCustoId')) {
            return redirect("empresa/home");
        }
    }
}
