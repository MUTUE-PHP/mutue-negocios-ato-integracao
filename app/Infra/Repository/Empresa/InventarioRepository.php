<?php

namespace App\Infra\Repository\Empresa;

use App\Models\empresa\Inventario;

class InventarioRepository
{
    public function getInventarios($filtro = []){
        $inventarios = Inventario::with(['user', 'armazem', 'inventarioItems'])
            ->where('empresa_id', auth()->user()->empresa_id)->get();
        return $inventarios;
    }
    public function getUltimoInventario(){
        return Inventario::where('empresa_id', auth()->user()->empresa_id)
            ->orderBy('id', 'DESC')->limit(1)->first();
    }
}
