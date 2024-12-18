<?php

namespace App\Http\Controllers\empresa\NotasEntrega;

use App\Application\UseCase\Empresa\Faturas\GetFaturaPelaNumeracaoDocumento;
use App\Application\UseCase\Empresa\NotaEntrega\EmitirNotaEntrega;
use App\Application\UseCase\Empresa\NotaEntrega\GetNotaEntrega;
use App\Application\UseCase\Empresa\NotaEntrega\GetNotasEntregas;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class NotaEntregaIndexController extends Component
{
    use LivewireAlert;
    public $numeracaoDocumento = null;
    public $search = null;

    public function updatedNumeracaoDocumento($value)
    {
        $this->numeracaoDocumento = strtoupper($value);
    }
    public function render()
    {
        $getNotasEntregas = new GetNotasEntregas(new DatabaseRepositoryFactory());
        $data['notasEntregas'] = $getNotasEntregas->execute($this->search);
        return view('empresa.notasEntregas.index', $data);
    }

    public function emitirNotaEntrega()
    {
        $numeracaoDocumento =  preg_replace('/\s+/', '', $this->numeracaoDocumento);
        if(strlen($numeracaoDocumento) < 5){
            $this->confirm('Número do documento não existe', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

        $primeirosDoisCaracteres = substr($numeracaoDocumento, 0, 2); // Obtém os primeiros dois caracteres
        $restoDaString = substr($numeracaoDocumento, 2); // Obtém o restante da string após os primeiros dois caracteres
        $numeracaoFatura = $primeirosDoisCaracteres . ' ' . $restoDaString; // Adiciona um espaço após os primeiros dois caracteres
        $getFatura = new GetFaturaPelaNumeracaoDocumento(new DatabaseRepositoryFactory());
        $fatura = $getFatura->execute($numeracaoFatura);
        if(!$fatura){
            $this->confirm('Número do documento não existe', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

        $this->imprimirNotaEntrega($fatura->id);

        $getFatura = new GetNotaEntrega(new DatabaseRepositoryFactory());
        $isNotaEntrega = $getFatura->execute($fatura->id);
        if(!$isNotaEntrega){
            $emitirNotaEntrega = new EmitirNotaEntrega(new DatabaseRepositoryFactory());
            $emitirNotaEntrega->execute($fatura);
        }
        $this->numeracaoDocumento = null;
    }

    public function imprimirNotaEntrega($faturaId){
        $DIR ="/upload/documentos/empresa/modelosFacturas/a4/";

        $filename = "notaEntrega";
        $reportController = new ReportShowController('pdf', $DIR);
        $report = $reportController->show(
            [
                'report_file' => $filename,
                'report_jrxml' => $filename . '.jrxml',
                'report_parameters' => [
                    'empresa_id' => auth()->user()->empresa_id,
                    'facturaId' => $faturaId,
                ]
            ]
        );

        $this->dispatchBrowserEvent('printPdf', ['data' => base64_encode($report['response']->getContent())]);
        unlink($report['filename']);
        flush();
    }
}
