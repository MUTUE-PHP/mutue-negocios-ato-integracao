<?php

namespace App\Repositories\Empresa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class ContaRepository
{
    public function getAll()
    {
        $response = Http::get(env('CONTA_CERTA') . '/api/listar-contas?empresa_id=1');
        if ($response->successful()) {
            return $response->json();
        }
        return [];
    }
    public function create($data){
        $response = Http::post(env('CONTA_CERTA') . "/api/criar-subconta",$data);
        if ($response->successful()) {
            return $response;
        } else {
            abort($response->status(), 'Falha na requisição à API externa');
        }
    }
    public function getConta($contas, $numeroConta){
        $array = collect($contas);
        return $array->first(function ($item) use($numeroConta){
            return isset($item['conta']['numero']) && $item['conta']['numero'] == $numeroConta;
        });
    }

    public function getSequencia(Model $model)
    {
        $data = $model->where('empresa_id', auth()->user()->empresa_id)
            ->orderBy('id', 'desc')
            ->first();
        if(!$data) return 1;
        return $data->sequencia += 1;
    }

}
