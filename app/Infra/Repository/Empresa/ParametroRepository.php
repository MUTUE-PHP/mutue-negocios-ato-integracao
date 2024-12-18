<?php

namespace App\Infra\Repository\Empresa;

use App\Models\empresa\Parametro;
use App\Models\empresa\Parametro as ParametroDatabase;
use App\Models\empresa\ParametroImpressao as ParametroImpressaoDatabase;

class ParametroRepository
{
    public function getFormatoImpressaoFaturacao(){
        return Parametro::where('empresa_id', auth()->user()->empresa_id)
            ->orwhere('empresa_id', null)
            ->first();
    }
    public function getNumeroSerieDocumento(){
        return Parametro::where('label', 'numero_serie_documento')
            ->where('empresa_id', auth()->user()->empresa_id)->first();
    }
    public function getAnoFaturacao(){
        return Parametro::where('label', 'ano_de_faturacao')
            ->where('empresa_id', auth()->user()->empresa_id)->first();
    }
    public function getHabilitadoNotaEntrega(){
        return Parametro::where('label','habilitar_nota_entrega')
            ->where('empresa_id', auth()->user()->empresa_id)
            ->first();
    }
    public function atualizarParametro($parametroId, $valor, $label){

        $parametroExiste = ParametroDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('label', $label)
            ->first();
        if($parametroExiste){
            return ParametroDatabase::where('id', $parametroId)->update([
                'valor' => $valor
            ]);
        }else{


            $parametro = ParametroDatabase::where('empresa_id', null)
                ->where('label', $label)
                ->first();

            return ParametroDatabase::create([
                'designacao' => $parametro['designacao'],
                'valor' => $valor,
                'valorSelects' => $parametro['valorSelects'],
                'vida' => $parametro['vida'],
                'empresa_id' => auth()->user()->empresa_id,
                'label' => $parametro['label'],
                'type' => $parametro['type'],
            ]);
        }

    }
    public function getParametro($parametroId){
        return ParametroDatabase::where('id', $parametroId)->first();
    }
    public function getParametroPeloLabel(){
        return ParametroDatabase::where('label', 'layoutVenda')
            ->where('empresa_id', auth()->user()->empresa_id)
            ->first();

    }
    public function GetParametroPeloLabelNoParametro($label){
        $parametro = ParametroDatabase::where('label', $label)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->first();
        if(!$parametro) {
            return ParametroDatabase::where('label', $label)
                ->where('empresa_id', null)
                ->first();
        }
        return $parametro;
    }

    public function getParametrosEmpresa(){
        $parametros = ParametroDatabase::where('empresa_id', 1)
            ->get();
       return $parametros;
    }
}
