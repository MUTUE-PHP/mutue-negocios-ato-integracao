<?php

namespace App\Http\Controllers\empresa\CartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\AtualizarBonusCartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\EliminarBonusCartaoCliente;
use App\Application\UseCase\Empresa\CartaoCliente\GetBonuCartaoClienteRange;
use App\Application\UseCase\Empresa\CartaoCliente\GetBonusCartaoCliente;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use Illuminate\Http\Request;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BonusCartaoClienteIndexController extends Component
{

    use LivewireAlert;

    public $search = null;
    public $bonusId;
    public $bonus;
    public $status_id = 1;//ativo
    protected $listeners = ['deletarBonus'];
    public $rangesBonus = [];

    public function render()
    {
        $getBonusCartaoCliente = new GetBonusCartaoCliente(new DatabaseRepositoryFactory());
        $data['bonusCartaoCliente'] = $getBonusCartaoCliente->execute();
        $getBonusRangeCartaoCliente = new GetBonuCartaoClienteRange(new DatabaseRepositoryFactory());
        $rangesBonusData = $getBonusRangeCartaoCliente->execute();
        if ($rangesBonusData) {
            foreach ($rangesBonusData as $bonu) {
                array_push($this->rangesBonus, [
                    'min' => $bonu['valorInicial'],
                    'max' => $bonu['valorFinal'],
                    'valor' => $bonu['valorBonus']
                ]);
            }
        }else{
            array_push($this->rangesBonus, [
                'min' => 0,
                'max' => 1000000000000000000000000000000000000000000,
                'valor' => 0
            ]);
        }
        $viewRange = 'bonusCartaoClienteRangeIndex';
        $viewBonus = 'bonusCartaoClienteIndex';
        return view("empresa.CartaoClientes.{$viewBonus}", $data);
    }
    public function atualizarBonusRangeCartaoCliente(){
        dd($this->rangesBonus);
    }
    public function updatedBonus($bonus){
        if($bonus > 100){
            $this->alert('warning', 'O bônus vai de 0 á 100%');
            $this->bonus = 100;
        }
    }
    public function deletarBonus($data)
    {
        if ($data['value']) {
            $atualizarBonusCompraCartaoCliente = new EliminarBonusCartaoCliente(new DatabaseRepositoryFactory());
            $output = $atualizarBonusCompraCartaoCliente->execute($this->bonusId);
            if($output)
                $this->confirm('Operação realizada com sucesso', [
                    'showConfirmButton' => false,
                    'showCancelButton' => false,
                    'icon' => 'success'
                ]);

        }
    }
    public function modalEditaBonus($valor){
        $this->bonus = $valor['bonus'];
    }
    public function modalAddBonus(){
        $this->bonus = null;
    }
    public function atualizarBonusCartaoCliente(){
        $atualizarBonusCompraCartaoCliente = new AtualizarBonusCartaoCliente(new DatabaseRepositoryFactory());
        $output = $atualizarBonusCompraCartaoCliente->execute(new Request([
            'bonus' => $this->bonus,
            'status_id' => $this->status_id
        ]));
        if($output)
            $this->confirm('Operação realizada com sucesso', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'success'
            ]);
    }
    public function modalDel($bonusId)
    {
        $this->bonusId = $bonusId;
        $this->confirm('Deseja eliminar o bônus?', [
            'onConfirmed' => 'deletarBonus',
            'cancelButtonText' => 'Não',
            'confirmButtonText' => 'Sim',
        ]);
    }


}
