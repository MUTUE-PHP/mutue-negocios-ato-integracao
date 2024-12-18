<?php

namespace App\Http\Controllers\empresa\Operacao;

use App\Http\Controllers\empresa\ReportShowController;
use App\Models\empresa\NotaCredito;
use Livewire\Component;

class RetificacaoDocumentoFaturaIndexController extends Component
{
    use TraitPrintAnulacaoFatura;
    use TraitPrintNotaCredito;

    public function render(){
        $data['facturas'] = NotaCredito::with(['factura', 'user'])
            ->where('facturaId', '!=', NULL)
            ->whereHas('factura', function($query){
                $query->where('retificado', 'Y');
            })->where('empresa_id', auth()->user()->empresa_id)
            ->get();
        return view('empresa.operacao.documentosRetificacaoFaturaIndex', $data);
    }
    public function imprimirFaturaNotaCreditoRetificacao($notaCreditoId){
        $this->printNotaCredito($notaCreditoId);
    }
}
