<?php

namespace App\Http\Controllers;

use App\Models\empresa\LogAcesso;

trait TraitLogAcesso
{
    public function logAcesso(){
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('Africa/Luanda');
        $descricao = "No dia " . strftime("%d de %B de %Y", strtotime(date('Y-m-d'))) . " o Senhor(a) " . auth()->user()->name . " fez um acesso ao sistema mutue aeroporto as " . date('h') . " horas " . date('i') . " minutos e " . date("s") . " segundos";
        LogAcesso::create([
            'user_name' => auth()->user()->name,
            'descricao' => $descricao,
            'ip' => request()->ip(), //$_SERVER['REMOTE_ADDR'],
            'browser' => $_SERVER['HTTP_USER_AGENT'],
            'rota_acessado' => $_SERVER['HTTP_REFERER'],
            'maquina' => NULL,
            'user_id' => auth()->user()->id
        ]);

    }

}
