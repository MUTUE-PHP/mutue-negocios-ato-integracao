<?php

namespace App\Infra\Repository\Empresa;

use App\Domain\Entity\Empresa\SequenciaDocumento;
use App\Enums\EnumTipoDocumento;
use App\Models\empresa\SequenciaDocumentoDatabase;

class SequenciaDocumentoRepository
{
    public function salvar(SequenciaDocumento $sequenciaDocumento)
    {

        return SequenciaDocumentoDatabase::create([
            'sequencia' => $sequenciaDocumento->getSequencia(),
            'tipo_documento' => $sequenciaDocumento->getTipoDocumento(),
            'serie_documento' => $sequenciaDocumento->getSerieDocumento(),
            'empresa_id' => auth()->user()->empresa_id ?? 158,
            'tipoDocumentoNome' => $sequenciaDocumento->getTipoDocumentoNome()
        ]);
    }
    public function findAll($search)
    {
        return SequenciaDocumentoDatabase::with('tipoDocumento')->where('empresa_id', auth()->user()->empresa_id ?? 158)
            ->orwhere('empresa_id', null)
            ->orderBy('id', 'DESC')
            ->search(trim($search))->paginate();
    }
    public function getSequenciaDocumento($id)
    {
        return SequenciaDocumentoDatabase::with('tipoDocumento')
            ->where('id', $id)
            ->first();
    }
}
