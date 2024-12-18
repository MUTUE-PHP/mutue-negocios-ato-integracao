<?php

namespace App\Http\Controllers\empresa\Proformas;

use App\Application\UseCase\Empresa\FormasPagamento\GetFormaPagamentoEmitirRecibo;
use App\Application\UseCase\Empresa\Proformas\ConverterProformaByFaturaRecibo;
use App\Http\Controllers\empresa\Faturacao\PrintFaturaAeroportuario;
use App\Http\Controllers\empresa\Faturacao\PrintFaturaCarga;
use App\Http\Controllers\empresa\Faturacao\PrintFaturaOutroServico;
use App\Http\Controllers\empresa\Faturacao\PrintFaturaServicoComerciais;
use App\Http\Controllers\TraitLogAcesso;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Factura as FaturaDatabase;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;


class ProformaIndexController extends Component
{
    use LivewireAlert;
    use PrintFaturaCarga;
    use PrintFaturaAeroportuario;
    use PrintFaturaOutroServico;
    use PrintFaturaServicoComerciais;
    use TraitLogAcesso;

    public $numeracaoFactura = null;
    public $formaPagamentos;
    public $temProforma = false;
    public $proforma = [
        'nomeCliente' => null,
        'formaPagamentoId' => 1,
        'nifCliente' => null,
        'numeracaoFactura' => null,
        'nomeProprietario' => null,
        'valorIliquido' => 0,
        'taxaIva' => 0,
        'valorImposto' => 0,
        'taxaRetencao' => 0,
        'valorRetencao' => 0,
        'total' => 0,
        'moeda' => null,
        'cambioDia' => 0,
        'contraValor' => 0,
    ];

    public function mount(){
        $getFormaPagamento = new GetFormaPagamentoEmitirRecibo(new DatabaseRepositoryFactory());
        $this->formaPagamentos = $getFormaPagamento->execute();
    }

    public function updatedNumeracaoFactura($numeracao)
    {
        $fatura = FaturaDatabase::where(function ($query) use ($numeracao) {
            $query->where('numeracaoFactura', 'LIKE', '%' . $numeracao)
                ->orWhere('codigoBarra', 'LIKE', '%' . $numeracao);
        })
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('tipo_documento', 3)
            ->first();

        if ($fatura && strlen($numeracao) > 4) {
            if ($fatura->anulado === 'Y') {
                $this->confirm('Não permitido converter, documento já foi anulado', [
                    'showConfirmButton' => true,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                return;
            }
            if ($fatura->convertido === 'Y') {
                $this->confirm('Fatura já convertida', [
                    'showConfirmButton' => true,
                    'showCancelButton' => false,
                    'icon' => 'warning'
                ]);
                return;
            } else {
                $fatura = (object) $fatura;
                $this->temProforma = true;
                $this->proforma['id'] = $fatura['id'];
                $this->proforma['nomeCliente'] = $fatura['nome_do_cliente'];
                $this->proforma['tipoFatura'] = $fatura['tipoFatura'];
                $this->proforma['nifCliente'] = $fatura['nif_cliente'];
                $this->proforma['numeracaoFactura'] = $fatura['numeracaoFactura'];
                $this->proforma['nomeProprietario'] = $fatura['nomeProprietario'];
                $this->proforma['valorIliquido'] = $fatura['valorIliquido'];
                $this->proforma['taxaIva'] = $fatura['taxaIva'];
                $this->proforma['valorImposto'] = $fatura['valorImposto'];
                $this->proforma['taxaRetencao'] = $fatura['taxaRetencao'];
                $this->proforma['valorRetencao'] = $fatura['valorRetencao'];
                $this->proforma['total'] = $fatura['total'];
                $this->proforma['moeda'] = $fatura['moeda'];
                $this->proforma['cambioDia'] = $fatura['cambioDia'];
                $this->proforma['contraValor'] = $fatura['contraValor'];
            }
        } else {
            $this->resetField();
            $this->temProforma = true;
        }
    }
    public function resetField(){
        $this->proforma = [
            'nomeCliente' => null,
            'formaPagamentoId' => 1,
            'nifCliente' => null,
            'numeracaoFactura' => null,
            'nomeProprietario' => null,
            'valorIliquido' => 0,
            'taxaIva' => 0,
            'valorImposto' => 0,
            'taxaRetencao' => 0,
            'valorRetencao' => 0,
            'total' => 0,
            'moeda' => null,
            'cambioDia' => 0,
            'contraValor' => 0,
        ];
    }

    public function render()
    {
        return view('empresa.proformas.index');
    }

    public function converterProforma()
    {
        if(!$this->temProforma){
            $this->confirm('Documento não encontrado', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        $converterProformaByFaturaRecibo = new ConverterProformaByFaturaRecibo(new DatabaseRepositoryFactory());
        $faturaId = $converterProformaByFaturaRecibo->execute($this->proforma);
        if($this->proforma['tipoFatura'] == 1){
            $this->printFaturaCarga($faturaId);
        }else if($this->proforma['tipoFatura'] == 2){
            $this->printFaturaAeroportuario($faturaId);
        }else if($this->proforma['tipoFatura'] == 3){
            $this->printFaturaOutroServico($faturaId);
        }else{
            $this->printFaturaServicoComercias($faturaId);
        }
        $this->resetField();
        $this->logAcesso();
        $this->numeracaoFactura = null;
        $this->temProforma = false;
    }
}
