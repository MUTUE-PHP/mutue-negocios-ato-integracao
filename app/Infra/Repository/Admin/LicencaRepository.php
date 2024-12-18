<?php

namespace App\Infra\Repository\Admin;
use App\Models\admin\Licenca as LicencaDatabase;

class LicencaRepository
{
    public function getLicenca($licencaId)
    {
        return LicencaDatabase::with(['taxaIva'])->where('id', $licencaId)
            ->first();
    }
    public function getLicencas()
    {
        return LicencaDatabase::with(['taxaIva'])->get();
    }

}
