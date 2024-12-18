<?php

namespace App\Http\Controllers\empresa\Operacao;

use App\Application\UseCase\Empresa\Operacao\EmitirAnulacaoFatura;
use App\Http\Controllers\TraitLogAcesso;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Factura as FaturaDatabase;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AnulacaoDocumentoFaturaComercialCreateController  extends Component
{

    use LivewireAlert;
    use TraitLogAcesso;
    use TraitPrintNotaCredito;

    public $numeracaoFactura = null;
    public $temFatura = false;
    public $observacao = null;
    public $faturaId;

    public $notaCredito = [
        'numeracaoFactura' => null,
        'nome_do_cliente' => null,
        'nif_cliente' => null,
        'nomeProprietario' => null,
        'valorIliquido' => 0,
        'taxaIva' => 0,
        'valorImposto' => 0,
        'taxaRetencao' => 0,
        'valorRetencao' => 0,
        'total' => 0,
        'moeda' => null,
        'cambioDia' => 0,
        'contraValor' => 0
    ];
    public function mount($id){

        $fatura =FaturaDatabase::with(['facturas_items'])
            ->where('id', $id)
            ->where('anulado', '!=', 'Y')
            ->where('empresa_id', auth()->user()->empresa_id)->first();
        

            

        if(!$fatura) return redirect()->route('facturasServicoComercial.index');
        $fatura = $fatura->toArray();
        $this->temFatura = true;
        $this->notaCredito = $fatura;
        $this->notaCredito['facturaId'] = $fatura['id'];
        $this->numeracaoFactura = $fatura['numeracaoFactura'];
        $this->faturaId = $fatura['id'];
    }
    public function render()
    {
        return view('empresa.operacao.documentosAnuladosFaturaComercialCreate');
    }

    public function updatedNumeracaoFactura($numeracao)
    {
        if (strlen($numeracao) > 4) {
            $fatura = FaturaDatabase::with(['facturas_items'])->where(function ($query) use ($numeracao) {
                $query->where('numeracaoFactura', 'LIKE', '%' . $numeracao)
                    ->orWhere('codigoBarra', 'LIKE', '%' . $numeracao);
            })->where('empresa_id', auth()->user()->empresa_id)->first();

            if ($fatura) {
                if($fatura->anulado == 'Y'){
                    $this->confirm('Fatura já foi anulado', [
                        'showConfirmButton' => true,
                        'showCancelButton' => false,
                        'icon' => 'warning'
                    ]);
                    $this->temFatura = false;
                    return;
                }
                $this->temFatura = true;
                $this->notaCredito = $fatura;
            }
        }
    }

    public function anularFatura()
    {
        $rules = [
            'observacao' => 'required',
        ];
        $messages = [
            'observacao.required' => 'campo obrigatório',
        ];
        $this->validate($rules, $messages);

        if ($this->temFatura) {
            $faturaAnulado = FaturaDatabase::where('numeracaoFactura', 'LIKE', '%' . $this->numeracaoFactura)
                ->where('empresa_id', auth()->user()->empresa_id)
                ->where('anulado', 'Y')
                ->first();


            if ($faturaAnulado) {
                $this->confirm('Fatura já foi anulado', [
                    'showConfirmButton' => true,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                return;
            }
            $recibo = DB::table('recibos')->where('facturaId', $this->notaCredito['facturaId'])
                ->where('anulado', 'N')->first();

            if ($recibo) {
                $this->confirm('Fatura não pode ser anulado, contém recibo', [
                    'showConfirmButton' => true,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                return;
            }

            $this->notaCredito['observacao'] = $this->observacao;
            $this->notaCredito['faturaId'] = $this->faturaId;



            $emitirAnulacaoFatura = new EmitirAnulacaoFatura(new DatabaseRepositoryFactory());
            $notaCreditoId = $emitirAnulacaoFatura->execute($this->notaCredito);
            if ($notaCreditoId) {
                $this->logAcesso();
                $this->printNotaCredito($notaCreditoId);
            }
        }
    }

}
