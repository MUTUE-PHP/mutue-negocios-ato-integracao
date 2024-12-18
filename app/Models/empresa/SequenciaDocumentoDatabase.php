<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class SequenciaDocumentoDatabase extends Model
{

    protected $table = "sequencias_documentos";

    protected $fillable = [
        'sequencia',
        'empresa_id',
        'tipo_documento',
        'tipoDocumentoNome',
        'serie_documento'
    ];

    public function tipoDocumento(){
        return $this->belongsTo(Tipodocumentosequencia::class, 'tipo_documento');
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";
        $query->where(function ($query) use ($term) {
            $query->where("tipo_documento", "like", $term)
                ->orwhere("serie_documento", "like", $term);
        });
    }
}
