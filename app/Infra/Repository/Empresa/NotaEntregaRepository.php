<?php

namespace App\Infra\Repository\Empresa;

use App\Models\empresa\NotaEntrega as NotaEntregaDatabase;

class NotaEntregaRepository
{

    public function getNotasEntregas($search){
        return NotaEntregaDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->search(trim($search))
            ->paginate();
    }
    public function getNotaEntrega($faturaId){
        return NotaEntregaDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('factura_id', $faturaId)
            ->where('operador_id', auth()->user()->id)
            ->first();
    }
    public function emitirNotaEntrega($fatura){

        return NotaEntregaDatabase::create([
            'numeracao_documento' => $fatura->numeracaoFactura,
            'operador_nome' => auth()->user()->name,
            'operador_id' => auth()->user()->id,
            'factura_id' => $fatura->id,
            'empresa_id' => auth()->user()->empresa_id,
        ]);
    }
}
