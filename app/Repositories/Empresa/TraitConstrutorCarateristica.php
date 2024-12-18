<?php

namespace App\Repositories\Empresa;

use Illuminate\Support\Facades\DB;

Trait TraitConstrutorCarateristica
{

    public function construirCaracteristicas($produto){
//        $categorias = DB::connection('mysql2')->table('categoriacaracteristicas')->get();
        $valoresCarateristicas = DB::connection('mysql2')->table('valorcaracteristicas_produtos')->where('produto_id', $produto['id'])
            ->join('categoriacaracteristicas', 'valorcaracteristicas_produtos.valor_caracteristica_id', '=', 'categoriacaracteristicas.id')
            ->get();
        $categoriaData = [];
        foreach ($valoresCarateristicas as $categoria){
            $carateristicasData = DB::connection('mysql2')->table('valorcaracteristicas')->where('categoria_caracteristica_id', $categoria->id)->get();
            $array = [];
            foreach ($carateristicasData as $data){
                array_push($array, [
                    'id' => $data->id,
                    'designacao' => $data->designacao,
                ]);
            }
            array_push($categoriaData, [
                'id' => $categoria->id,
                'designacao' => $categoria->designacao,
                'caracteristicas' => $array
            ]);
        }
        return $categoriaData;

        dd($categoriaData);

//        dd($valoresCarateristicas);
//        $categoriaData = [];
//        if(count($valoresCarateristicas) <= 0) return $categoriaData;
//        foreach ($categorias as $key=> $categoria){
//            $caracteristicas = [];
//            if(count($valoresCarateristicas) <= 0){
//                break;
//            }
//
//            foreach ($valoresCarateristicas as $key=> $valorCategoria) {
//                $carateristicasData = DB::connection('mysql2')->table('valorcaracteristicas')->where('categoria_caracteristica_id', $valorCategoria->valor_caracteristica_id)->get();
//                $array = [];
//
//                foreach ($carateristicasData as $data){
//                    array_push($caracteristicas, [
//                        'id' => $data->id,
//                        'designacao' => $data->designacao,
//                    ]);
//                }
//                if($valorCategoria->valor_caracteristica_id === $categoria->id){
//                    unset($valoresCarateristicas[$key]);
//                }
//            }
//
//            if(count($caracteristicas) > 0){
//                array_push($categoriaData, [
//                    'id' => $categoria->id,
//                    'designacao' => $categoria->designacao,
//                    'caracteristicas' => $caracteristicas
//                ]);
//            }
//
//        }
//        dd($categoriaData);
        return $categoriaData;
    }

}
