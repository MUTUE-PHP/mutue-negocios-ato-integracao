<?php

namespace App\Http\Controllers\empresa\Operacao;

use App\Models\empresa\NotaCredito;
use Livewire\Component;

class AnulacaoDocumentoReciboIndexController extends Component
{
    public function render(){
        $data['recibos'] = NotaCredito::with(['factura', 'user', 'recibo'])->where('reciboId', '!=', NULL)
            ->where('empresaId', auth()->user()->empresa_id)
            ->paginate();
        return view('empresa.operacao.documentosAnuladosReciboIndex', $data);
    }


}
