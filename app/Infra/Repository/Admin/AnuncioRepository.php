<?php

namespace App\Infra\Repository\Admin;
use App\Models\admin\Anuncio as AnuncioDatabase;
use Carbon\Carbon;

class AnuncioRepository
{

    public function getAnuncios(){
        return AnuncioDatabase::paginate();
    }
    public function getAnunciosPorDataValidas(){
        return AnuncioDatabase::where('data_final', '>=', Carbon::now())->paginate();
    }
}
