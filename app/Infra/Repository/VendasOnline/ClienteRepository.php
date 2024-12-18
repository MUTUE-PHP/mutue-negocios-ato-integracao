<?php

namespace App\Infra\Repository\VendasOnline;
use App\Models\empresa\Cliente as ClienteDatabase;

class ClienteRepository
{

    public function getClientePeloUserId($userId)
    {
        return ClienteDatabase::where('user_id', $userId)
            ->first();
    }

}
