<?php

namespace App\Infra\Traits;

use Illuminate\Support\Facades\DB;

trait TraitRuleUnique
{
    public static function unique($tabela, $attr, $campo , $id = null){
        return DB::table($tabela)->where($attr, $campo)
            ->where(function($query) use($id){
                if($id) $query->where('id', '!=', $id);
            })->where('empresa_id', auth()->user()->empresa_id??1)
            ->where('deleted_at',null)
            ->first();
    }
    public static function unique2($tabela, $attr, $campo , $id = null){
        return DB::table($tabela)->where($attr, $campo)
            ->where(function($query) use($id){
                if($id) $query->where('id', '!=', $id);
            })->first();
    }
    public static function unique3($tabela, $attr, $campo , $id = null){
        return DB::table($tabela)->where($attr, $campo)
            ->where(function($query) use($id){
                if($id) $query->where('id', '!=', $id);
            })->where('empresaId', auth()->user()->empresa_id)
            ->where('deleted_at',null)
            ->first();
    }

}
