<?php

namespace App\Infra\Repository\Admin;
use App\Models\admin\User as UserAdminDatabase;

class UserRepository
{
    public function buscarAdminParaNotificacaoAtivacaoLicenca(){
        return UserAdminDatabase::where('notificarAtivacaoLicenca', 'Y')->pluck('email')->toArray();
    }
}
