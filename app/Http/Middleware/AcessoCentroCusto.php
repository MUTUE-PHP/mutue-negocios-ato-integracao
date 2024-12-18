<?php

namespace App\Http\Middleware;

use App\Application\UseCase\Empresa\CentrosDeCusto\GetCentroCustoPelaEmpresa;
use Illuminate\Http\Request;
use App\Application\UseCase\Empresa\CentrosDeCusto\CadastrarCentroCusto;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Support\Facades\DB;
use Closure;

class AcessoCentroCusto
{
    public function handle($request, Closure $next)
    {
        $getCentroCusto = new GetCentroCustoPelaEmpresa(new DatabaseRepositoryFactory());
        $centroCusto = $getCentroCusto->execute();
        if(!$centroCusto){
            $cadastrarCentroCusto = new CadastrarCentroCusto(new DatabaseRepositoryFactory());
            $centroCusto = [
                'nome' => auth()->user()->empresa->nome,
                'endereco' => auth()->user()->empresa->endereco,
                'nif' => auth()->user()->empresa->nif,
                'cidade' => auth()->user()->empresa->cidade,
                'logotipo' => auth()->user()->empresa->logotipo,
                'email' => auth()->user()->empresa->email,
                'website' => auth()->user()->empresa->website,
                'telefone' => auth()->user()->empresa->pessoal_Contacto??auth()->user()->empresa->telefone1,
                'pessoaContato' => auth()->user()->empresa->pessoal_Contacto,
                'fileAlvara' => auth()->user()->empresa->file_alvara,
                'fileNif' => auth()->user()->empresa->file_nif,
                'statusId' => 1 //ativo
            ];
            $centroCusto = $cadastrarCentroCusto->execute(new Request($centroCusto));
            if($centroCusto){
                $request->session()->put('centroCustoId', $centroCusto['id']);
                return redirect("empresa/opcao/centros/custo");
            }
        }
        $user = DB::table('users_centro_custo')->where('user_id', auth()->user()->id)->first();
        if(!$user){
            $centroCusto = DB::table('centro_custos')->where('empresa_id', auth()->user()->empresa_id)
                ->first();
            DB::table('users_centro_custo')->insert([
                'user_id' => auth()->user()->id,
                'centro_custo_id' => $centroCusto->id,
                'status' => 'Y',
            ]);
        }
        if (!session()->has('centroCustoId')) {
            return response()->redirectToRoute('opcaoCentroCusto.index');
        }
        return $next($request);
    }
}
