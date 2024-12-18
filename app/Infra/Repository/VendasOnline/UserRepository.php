<?php

namespace App\Infra\Repository\VendasOnline;

use App\Domain\Entity\VendasOnline\PagamentoVendasOnline;
use App\Models\admin\User;
use App\Models\Portal\CarrinhoProduto;

class UserRepository
{
    public function getEmailsAdminParaNotificar()
    {
        return User::where('notificarAtivacaoLicenca', 'Y')
            ->pluck('email')->toArray();
    }
}
