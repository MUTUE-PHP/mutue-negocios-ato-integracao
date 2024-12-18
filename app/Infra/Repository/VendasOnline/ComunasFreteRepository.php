<?php

namespace App\Infra\Repository\VendasOnline;
use App\Domain\Entity\VendasOnline\ComunaFrete;
use App\Models\empresa\ComunasFrete as ComunasFreteDatabase;
use Illuminate\Support\Str;

class ComunasFreteRepository
{
    public function getComunasFretePelMunicipio($municipioId = 1)
    {
        return ComunasFreteDatabase::where('municipioId', $municipioId)
            ->where('statusId', 1)
            ->get();
    }
    public function getComunasFrete($search = null)
    {
        return ComunasFreteDatabase::with(['municipio', 'municipio.provincia'])
            ->orderBy('designacao')
            ->paginate();
    }

    public function getComunasFreteSemPaginacao($search = null)
    {
        return ComunasFreteDatabase::with(['municipio', 'municipio.provincia'])
            ->where('statusId', 1)
            ->orderBy('designacao')
            ->get();
    }
    public function getComunaFrete($comunaId)
    {
        return ComunasFreteDatabase::with(['municipio'])
            ->where('id', $comunaId)
            ->first();
    }
    public function salvar(ComunaFrete $comunaFrete){


        return ComunasFreteDatabase::create([
            'designacao' => Str::title($comunaFrete->getDesignacao()),
            'municipioId' => $comunaFrete->getMunicipioId(),
            'valor_entrega' => $comunaFrete->getValorEntrega(),
            'statusId' => $comunaFrete->getStatusId(),
        ]);
    }
    public function atualizar(ComunaFrete $comunaFrete , $municipioId){
        return ComunasFreteDatabase::where('id', $municipioId)->update([
            'designacao' => Str::title($comunaFrete->getDesignacao()),
            'municipioId' => $comunaFrete->getMunicipioId(),
            'valor_entrega' => $comunaFrete->getValorEntrega(),
            'statusId' => $comunaFrete->getStatusId(),
        ]);
    }
    public function delete($comunaId){
        return ComunasFreteDatabase::where('id', $comunaId)->delete();
    }
}
