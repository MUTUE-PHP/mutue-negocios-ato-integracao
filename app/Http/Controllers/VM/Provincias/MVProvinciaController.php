<?php

namespace App\Http\Controllers\VM\Provincias;

use App\Http\Controllers\Controller;
use App\Models\empresa\Provincia;

class MVProvinciaController  extends Controller
{
    public function listarProvincias(){
        return Provincia::get();
    }
}
