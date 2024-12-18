<?php

namespace App\Infra\Repository\Empresa;
use App\Domain\Entity\Empresa\CentrosDeCusto\CentroCusto;
use App\Models\empresa\CentroCusto as CentroCustoDatabase;
use App\Models\empresa\User;
use Illuminate\Support\Str;

class CentroCustoRepository
{
    public function getCentrosCusto()
    {
        return CentroCustoDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->orderBy('nome', 'asc')
            ->paginate();
    }
    public function getCentrosCustoUserAutenticado(){
        $user = User::with(['centrosCusto'])->find(auth()->user()->id);
        return $user->centrosCusto;
    }
    public function getCentrosCustoSemPaginacao(){
        return CentroCustoDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->orderBy('nome', 'asc')
            ->get();
    }
    public function getCentroCustoPelaEmpresa(){
        return CentroCustoDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->first();
    }

    public function getCentroCusto($centroCustoId)
    {
        return CentroCustoDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('id', $centroCustoId)
            ->first();
    }
    public function getCentroCustoUuid($uuid)
    {
        return CentroCustoDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('uuid', $uuid)
            ->first();
    }
    public function salvar(CentroCusto $centroCusto)
    {
        return CentroCustoDatabase::create([
            'uuid' => Str::uuid(),
            'nome' => $centroCusto->getNome(),
            'empresa_id' => auth()->user()->empresa_id,
            'status_id' => 1,
            'endereco' => $centroCusto->getEndereco(),
            'nif' => $centroCusto->getNif(),
            'cidade' => $centroCusto->getCidade(),
            'logotipo' => $centroCusto->getLogotipo(),
            'email' => $centroCusto->getEmail(),
            'website' => $centroCusto->getWebsite(),
            'telefone' => $centroCusto->getTelefone(),
            'pessoa_de_contacto' => $centroCusto->getPessoaContato(),
            'file_alvara' => $centroCusto->getFileAlvara(),
            'file_nif' => $centroCusto->getFileNif(),
        ]);
    }
    public function update(CentroCusto $centroCusto, $uuid){

        return CentroCustoDatabase::where('uuid', $uuid)->update([
            'nome' => $centroCusto->getNome(),
            'endereco' => $centroCusto->getEndereco(),
            'nif' => $centroCusto->getNif(),
            'cidade' => $centroCusto->getCidade(),
            'status_id' => $centroCusto->getStatusId(),
            'logotipo' => $centroCusto->getLogotipo(),
            'email' => $centroCusto->getEmail(),
            'website' => $centroCusto->getWebsite(),
            'telefone' => $centroCusto->getTelefone(),
            'pessoa_de_contacto' => $centroCusto->getPessoaContato(),
            'file_alvara' => $centroCusto->getFileAlvara(),
            'file_nif' => $centroCusto->getFileNif(),
        ]);
    }
    public function delete($centroCustoId){
        return CentroCustoDatabase::where('id', $centroCustoId)
            ->where('empresa_id', auth()->user()->empresa_id)->delete();
    }
}
