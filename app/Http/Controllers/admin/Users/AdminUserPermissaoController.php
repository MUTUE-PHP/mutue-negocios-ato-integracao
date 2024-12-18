<?php

namespace App\Http\Controllers\admin\Users;

use App\Application\UseCase\Admin\users\GetPermissoes;
use App\Http\Controllers\admin\ReportShowAdminController;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Admin\DatabaseRepositoryFactory;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminUserPermissaoController extends Component
{
    public function render(){
        $getPermissoes = new GetPermissoes(new DatabaseRepositoryFactory());
        $data['permissoes'] = $getPermissoes->execute();
        return view('admin.usuarios.permissao', $data)->layout('layouts.appAdmin');
    }
    public function imprimirPermissoes(){

        $empresa = DB::connection('mysql')->table('empresas')->where('id', 1)->first();

        $logotipo = public_path() . '/upload//' . $empresa->logotipo;
//        $caminho = public_path() . '/upload/documentos/admin/relatorios/';

        $filename = "permissoes";

        $reportController = new ReportShowAdminController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'diretorio' => $logotipo
                ]

            ]
        );

        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();

    }
}
