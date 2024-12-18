<?php

namespace App\Infra\Repository\Admin;

use App\Models\admin\LogsUpdatePassword as LogsUpdatePasswordDatabase;

class LogsUpdatePasswordClientRepository
{
    public function getLogsUpdatePassword()
    {
        return LogsUpdatePasswordDatabase::with(['empresa', 'user'])->paginate();
    }

}
