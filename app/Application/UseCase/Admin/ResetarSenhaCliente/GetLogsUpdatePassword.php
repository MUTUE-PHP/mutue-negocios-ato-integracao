<?php

namespace App\Application\UseCase\Admin\ResetarSenhaCliente;

use App\Domain\Factory\Admin\RepositoryFactory;
use App\Infra\Repository\Admin\ClienteRepository;
use App\Infra\Repository\Admin\LogsUpdatePasswordClientRepository;
use Illuminate\Http\Request;

class GetLogsUpdatePassword
{
    private LogsUpdatePasswordClientRepository $logsUpdatePasswordClientRepository;
    public function __construct(RepositoryFactory $repositoryFactory){
        $this->logsUpdatePasswordClientRepository = $repositoryFactory->createUpdatePasswordClientRepository();
    }
    public function execute(){
        return $this->logsUpdatePasswordClientRepository->getLogsUpdatePassword();
    }

}
