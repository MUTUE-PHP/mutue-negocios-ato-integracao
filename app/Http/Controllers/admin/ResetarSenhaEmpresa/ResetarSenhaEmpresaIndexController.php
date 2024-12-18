<?php

namespace App\Http\Controllers\admin\ResetarSenhaEmpresa;

use App\Application\UseCase\Admin\Cliente\GetClientes;
use App\Application\UseCase\Admin\ResetarSenhaCliente;
use App\Application\UseCase\Admin\ResetarSenhaCliente\GetLogsUpdatePassword;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ResetarSenhaEmpresaIndexController extends Component
{ 

 

    use LivewireAlert;
    public function boot(){
      
    }
    public function render(){

      
        $logsUpdatePasswordClient = new GetLogsUpdatePassword(new DatabaseRepositoryFactory());
        $data['logsUpdatePasswordClient'] = $logsUpdatePasswordClient->execute();
        return view('admin.resetarSenhaEmpresa.index', $data)->layout('layouts.appAdmin');
    }

}
