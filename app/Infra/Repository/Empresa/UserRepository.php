<?php

namespace App\Infra\Repository\Empresa;

use App\Models\empresa\User as UserDatabase;
use App\Models\empresa\UsersCentrosCusto as UsersCentrosCustoDatabase;

class UserRepository
{
    public function getUserPeloUuid($uuid){
        return UserDatabase::with(['centrosCusto'])->where('uuid', $uuid)
            ->where('empresa_id', auth()->user()->empresa_id)
            ->first();
    }
    public function atualizarPermissaoCentroCusto($centroCustoId, $userId){
        $centroCusto = UsersCentrosCustoDatabase::where('centro_custo_id', $centroCustoId)
            ->where('user_id', $userId)
            ->first();

        if(!$centroCusto){
           return UsersCentrosCustoDatabase::create([
                'centro_custo_id' => $centroCustoId,
                'user_id' => $userId,
               'status' => 'Y'
            ]);
        }else{
            if($centroCusto->status == 'Y'){
                $status = 'N';
            }else{
                $status = "Y";
            }
            return UsersCentrosCustoDatabase::where('centro_custo_id', $centroCustoId)
                ->where('user_id', $userId)->update([
                'status' => $status
            ]);
        }

    }

}
