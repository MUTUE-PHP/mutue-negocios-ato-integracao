<?php

namespace App\Http\Controllers\empresa\Operacao;

use App\Application\UseCase\Empresa\Operacao\EmitirAnulacaoFatura;
use App\Application\UseCase\Empresa\Operacao\EmitirAnulacaoRecibo;
use App\Http\Controllers\TraitLogAcesso;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Factura as FaturaDatabase;
use App\Models\empresa\Recibos;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AnulacaoDocumentoReciboCreateController extends Component
{
    use LivewireAlert;
    use TraitLogAcesso;

    public $numeracaoRecibo = null;
    public $temRecibo = false;
    public $recibo = [
        'nomeCliente' => null,
        'nifCliente' => null,
        'nomeProprietario' => null,
        'totalFatura' => 0,
        'numFatura' => null,
        'numeracaoRecibo' => null,
        'descricao' => null
    ];

    public function render()
    {
        return view('empresa.operacao.documentosAnuladosReciboCreate');
    }
    public function updatedNumeracaoRecibo($numeracao)
    {
        $recibo = Recibos::with(['factura'])->where(function ($query) use ($numeracao) {
            $query->where('numeracaoRecibo', 'LIKE', '%' . $numeracao);
        })->where('empresaId', auth()->user()->empresa_id)->first();

        if ($recibo && strlen($numeracao) > 4) {
            if ($recibo->anulado == 'Y') {
                $this->confirm('Recibo já foi anulado', [
                    'showConfirmButton' => true,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                $this->temRecibo = false;
                return;
            } else {
                $this->temRecibo = true;
                $recibo = (object)$recibo;
                $this->recibo['id'] = $recibo['id'];
                $this->recibo['reciboId'] = $recibo['id'];
                $this->recibo['nomeCliente'] = $recibo['nomeCliente'];
                $this->recibo['nifCliente'] = $recibo['nifCliente'];
                $this->recibo['numeracaoRecibo'] = $recibo['numeracaoRecibo'];
                $this->recibo['nomeProprietario'] = $recibo['factura']['nomeProprietario'];
                $this->recibo['numFatura'] = $recibo['factura']['numeracaoFactura'];
                $this->recibo['totalFatura'] = $recibo['totalFatura'];
            }
        } else {
            $this->resetField();
        }
    }
    public function resetField(){
        $this->recibo = [
            'nomeCliente' => null,
            'nifCliente' => null,
            'nomeProprietario' => null,
            'totalFatura' => 0,
            'numFatura' => null,
            'numeracaoRecibo' => null,
            'descricao' => null
        ];
    }
    public function anularRecibo()
    {
        if ($this->temRecibo) {
            $reciboAnulado = Recibos::where(function ($query) {
                $query->where('numeracaoRecibo', 'LIKE', '%' . $this->numeracaoRecibo);
            })->where('empresaId', auth()->user()->empresa_id)
                ->where('anulado', 'Y')
                ->first();

            if ($reciboAnulado) {
                $this->confirm('Recibo já foi anulado', [
                    'showConfirmButton' => true,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                return;
            }

            $emitirAnulacaoRecibo = new EmitirAnulacaoRecibo(new DatabaseRepositoryFactory());
            $anulacaoDocumento = $emitirAnulacaoRecibo->execute($this->recibo);
            $this->logAcesso();
            if ($anulacaoDocumento) {
                $this->resetField();
                $this->confirm('Operação realizada com sucesso', [
                    'showConfirmButton' => true,
                    'showCancelButton' => false,
                    'icon' => 'success'
                ]);
                return;
            }
        }

    }
}
