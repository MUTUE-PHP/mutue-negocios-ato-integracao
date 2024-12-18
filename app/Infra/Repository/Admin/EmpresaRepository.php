<?php

namespace App\Infra\Repository\Admin;
use App\Models\admin\Anuncio as AnuncioDatabase;
use App\Models\admin\Empresa;
use Carbon\Carbon;

class EmpresaRepository
{

    public function getEmpresa(){
        return Empresa::where('id', 1)->first();
    }
}
