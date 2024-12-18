<?php

namespace App\Infra\Repository\Empresa;

use App\Domain\Entity\Empresa\Empresa;
use App\Models\empresa\Empresa_Cliente;

class EmpresaRepository
{

    public function getEmpresas()
    {
        return Empresa_Cliente::where('empresa_id', auth()->user()->empresa_id)
            ->get();
    }

    public function salvar(Empresa $empresa)
    {
        return Empresa_Cliente::create([
            'nome' => $empresa->getNome(),
            'pessoal_Contacto' => $empresa->getTelefone1(),
            'telefone1' => $empresa->getTelefone1(),
            'telefone2' => $empresa->getTelefone2(),
            'telefone3' => $empresa->getTelefone3(),
            'pais_id' => $empresa->getPaisId(),
            'canal_id' => $empresa->getCanalId(),
            'status_id' => $empresa->getStatusId(),
            'nif' => $empresa->getNif(),
            'tipo_cliente_id' => $empresa->getTipoClienteId(),
            'tipo_regime_id' => $empresa->getRegimeId(),
            'logotipo' => $empresa->getLogotipo(),
            'website' => $empresa->getWebsite(),
            'email' => $empresa->getEmail(),
            'referencia' => $empresa->getReferencia(),
            'pessoa_de_contacto' => $empresa->getPessoaDeContato(),
            'cidade' => $empresa->getProvinciaId(),
            'file_alvara' => $empresa->getFileAlvara(),
            'file_nif' => $empresa->getFileNif(),
            'venda_online' => $empresa->getVendaOnline(),
        ]);
    }

}
