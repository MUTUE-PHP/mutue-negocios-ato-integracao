<?php

namespace App\Http\Controllers\empresa\Operacao;

use App\Models\empresa\NotaCredito;
use Livewire\Component;

class AnulacaoDocumentoFaturaIndexController extends Component
{
    use TraitPrintNotaCredito;
    public function render(){

        $data['facturas'] = NotaCredito::with(['factura', 'user'])
            ->where('empresa_id', auth()->user()->empresa_id)
             ->paginate();

             $data['totalNotaCredito'] = NotaCredito::count();

        return view('empresa.operacao.documentosAnuladosFaturaIndex', $data);
    }
    public function printNotaCreditoAnulacao($notaCreditoId){
        $this->printNotaCredito($notaCreditoId);
    }

}
