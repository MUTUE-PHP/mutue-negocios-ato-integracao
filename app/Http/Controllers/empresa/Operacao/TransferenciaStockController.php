<?php

namespace App\Http\Controllers\empresa\Operacao;
use App\Http\Controllers\empresa\ReportShowController;
use App\Models\empresa\TransferenciaProduto;
use Livewire\Component;

class TransferenciaStockController extends Component
{
    public function render(){
        $data['transferencias'] = TransferenciaProduto::with(['TransferenciaProdutoItems', 'user'])
            ->where('empresa_id', auth()->user()->empresa_id)
            ->orderBy('id', 'desc')
            ->paginate();
        $this->dispatchBrowserEvent('reloadTableJquery');

        return view('empresa.operacao.transferenciaProdutoIndex', $data);
    }
    public function imprimirTransferencia($transferenciaId){
        $logotipo = public_path() . '/upload//' . auth()->user()->empresa->logotipo;
        $filename = "transferenciaProduto";
        $reportController = new ReportShowController();
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'diretorio' => $logotipo,
                    'transferenciaId' => $transferenciaId,
                ]

            ]
        );

        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();

    }
}
