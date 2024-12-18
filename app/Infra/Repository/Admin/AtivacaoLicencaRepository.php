<?php

namespace App\Infra\Repository\Admin;

use App\Domain\Entity\Admin\AtivacaoLicenca;
use App\Models\admin\AtivacaoLicenca as AtivacaoLicencaDatabase;
use Illuminate\Support\Facades\DB;

class AtivacaoLicencaRepository
{
    public function ativarLicenca(AtivacaoLicenca $ativacaoLicenca){
        return AtivacaoLicencaDatabase::create([
            'licenca_id' => $ativacaoLicenca->getLicencaId(),
            'empresa_id' => $ativacaoLicenca->getEmpresaId(),
            'pagamento_id' => $ativacaoLicenca->getPagamentoId(),
            'data_inicio' => $ativacaoLicenca->getDataInicio(),
            'data_fim' => $ativacaoLicenca->getDataFim(),
            'data_activacao' => $ativacaoLicenca->getDataActivacao(),
            'user_id' => $ativacaoLicenca->getUserId(),
            'operador' => $ativacaoLicenca->getOperador(),
            'canal_id' => 2,
            'status_licenca_id' => $ativacaoLicenca->getStatusLicencaId(),
            'observacao' => $ativacaoLicenca->getObservacao()
        ]);
    }
    public function getUltimaAtivacaoLicenca($empresaId){
        return AtivacaoLicencaDatabase::where('data_inicio', '!=', null)
            ->where('status_licenca_id', 1)
            ->orderBy('id', 'DESC')
            ->limit(1)->first();
    }
    public function verificarUltimaLicencaGratis(){
        $empresaAdmin = DB::connection('mysql')->table('empresas')->where('referencia', auth()->user()->empresa->referencia)->first();
        return AtivacaoLicencaDatabase::where('empresa_id', $empresaAdmin->id)
            ->where('licenca_id', 1)
            ->orderBy('id', 'desc')->first();

    }
}
