<?php

namespace App\Infra\Repository\Admin;

use App\Models\admin\Bancos as BancoDatabase;

class BancoRepository
{
    public function getBancos()
    {
        return BancoDatabase::with(['coordernadaBancaria'])->where('status_id', 1)->get();
    }

}
