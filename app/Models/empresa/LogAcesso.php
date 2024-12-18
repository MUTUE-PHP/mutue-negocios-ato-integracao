<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class LogAcesso extends Model
{
    protected $connection = 'mysql2';
    protected $table="log_acessos";
    protected $guarded = ['id'];
    public function scopeFilter($query, $term)
    {
        $search = trim($term['search']) !== "" ? trim($term['search']) : null;
        $dataInicial = $term['dataInicial'] !== "" ? $term['dataInicial'] : null;
        $dataFinal = $term['dataFinal'] !== "" ? $term['dataFinal'] : null;

        return $query->where(function ($query) use ($search, $dataInicial, $dataFinal) {

            if($dataInicial && !$dataFinal){
                $query->whereDate('created_at', $dataInicial);
            }
            if($dataInicial && $dataFinal){
                $query->whereDate('created_at', '>=', $dataInicial)
                    ->whereDate('created_at', '<=', $dataFinal);
            }
            if ($search) {
                $query->where('user_name', 'like', '%' . $search . '%')
                    ->orwhere("descricao", "like", $search);
            }
        });
    }
}
