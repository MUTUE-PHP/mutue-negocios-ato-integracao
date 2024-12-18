<?php

namespace App\Infra\Repository\VendasOnline;
use App\Domain\Entity\VendasOnline\MunicipioFrete;
use App\Models\empresa\MunicipiosFrete as MunicipiosFreteDatabase;
use Illuminate\Support\Str;

class MunicipiosFreteRepository
{
    public function getMunicipiosFretePelaProvincia($provinciaId = 1)
    {
        return MunicipiosFreteDatabase::where('cidade_id', $provinciaId)
            ->where('status_id', 1)
            ->get();
    }
    public function getMunicipiosFrete($search)
    {
        return MunicipiosFreteDatabase::with(['provincia'])->paginate();
    }
    public function getMunicipioFrete($municipioId)
    {
        return MunicipiosFreteDatabase::with(['provincia'])
            ->where('id', $municipioId)
            ->first();
    }
    public function salvar(MunicipioFrete $municipioFrete){


        return MunicipiosFreteDatabase::create([
            'designacao' => Str::title($municipioFrete->getDesignacao()),
            'cidade_id' => $municipioFrete->getProvinciaId(),
            'valor_entrega' => $municipioFrete->getValorEntrega(),
            'status_id' => $municipioFrete->getStatusId(),
        ]);
    }
    public function atualizar(MunicipioFrete $municipioFrete , $idMunicipioFrete){
        return MunicipiosFreteDatabase::where('id', $idMunicipioFrete)->update([
            'designacao' => Str::title($municipioFrete->getDesignacao()),
            'cidade_id' => $municipioFrete->getProvinciaId(),
            'valor_entrega' => $municipioFrete->getValorEntrega(),
            'status_id' => $municipioFrete->getStatusId(),
        ]);
    }
    public function delete($idMunicipioFrete){
        return MunicipiosFreteDatabase::where('id', $idMunicipioFrete)->delete();
    }

}
