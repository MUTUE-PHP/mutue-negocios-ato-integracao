<?php

namespace App\Infra\Repository\Empresa;
use App\Domain\Entity\Empresa\CartaoCliente;
use App\Models\empresa\BonusCartaoCliente as BonusCartaoClienteDatabase;
use App\Models\empresa\BonusCartaoClienteRange;
use App\Models\empresa\CartaoCliente as CartaoClienteDatabase;
use Illuminate\Http\Request;

class CartaoClienteRepository
{
    public function salvar(CartaoCliente $cartaoCliente)
    {
        return CartaoClienteDatabase::create([
            'clienteId' => $cartaoCliente->getClienteId(),
            'saldo' => $cartaoCliente->getSaldo(),
            'numeroCartao' => $cartaoCliente->getNumeroCartao(),
            'dataEmissao' => $cartaoCliente->getDataEmissao(),
            'dataValidade' => $cartaoCliente->getDataValidade(),
            'numeracaoSequencia' => $cartaoCliente->getNumeracaoSequencia(),
            'empresaId' => auth()->user()->empresa_id ?? 53,

        ]);
    }
    public function temCartao($clienteId){
        return CartaoClienteDatabase::where('empresaId', auth()->user()->empresa_id)
            ->where('clienteId', $clienteId)
            ->orderBy('id', 'desc')
            ->first();
    }
    public function atualizarSaldo($numeroCartao, $saldo){
        return CartaoClienteDatabase::where('numeroCartao', $numeroCartao)->update([
            'saldo' => $saldo
        ]);
    }

    public function atualizarCartaoCliente(CartaoCliente $cartaoCliente, $id)
    {
        return CartaoClienteDatabase::where('clienteId', $cartaoCliente->getClienteId())
            ->orwhere('id', $id)
            ->update([
            'dataEmissao' => $cartaoCliente->getDataEmissao(),
            'dataValidade' => $cartaoCliente->getDataValidade(),
            'saldo' => $cartaoCliente->getSaldo(),
        ]);
    }
    public function getCartaoClientePeloNumero($numeroCartao){
        return CartaoClienteDatabase::with(['cliente'])
            ->where('empresaId', auth()->user()->empresa_id)
            ->whereHas('cliente',function($query)use($numeroCartao){
                $query->where('telefone_cliente', $numeroCartao);
            })->orwhere('numeroCartao', $numeroCartao)->first();
    }
    public function getCartaoClientePeloClienteId($clienteId){

        return CartaoClienteDatabase::with(['cliente'])
            ->where('empresaId', auth()->user()->empresa_id)
            ->where('clienteId', $clienteId)
            ->orderBy('id','desc')
            ->first();
    }
    public function getCartaoClientes($search)
    {
        return CartaoClienteDatabase::with(['cliente'])
            ->where('empresaId', auth()->user()->empresa_id ?? 53)
            ->search(trim($search))
            ->paginate();
    }

    public function getUltimaNumeracaoSequenciaCartaoCliente()
    {
        return CartaoClienteDatabase::where('empresaId', auth()->user()->empresa_id ?? 53)->count() + 1;
    }
    public function getBonuCartaoClienteRange(){
        return BonusCartaoClienteRange::with(['empresa', 'user'])
            ->where('empresa_id', auth()->user()->empresa_id)->get();

    }
    public function getBonusCartaoCliente(){
        return BonusCartaoClienteDatabase::with(['empresa', 'user'])->where('empresa_id', auth()->user()->empresa_id)->get();
    }
    public function getBonuCartaoCliente(){
        return BonusCartaoClienteDatabase::with(['empresa', 'user'])->where('empresa_id', auth()->user()->empresa_id)->first();
    }
    public function atualizarBonusCartaoCliente(Request $request){

        $bonus = $this->getBonusCartaoCliente();
        if(count($bonus) <= 0){
            return BonusCartaoClienteDatabase::create([
                'bonus' => $request->bonus,
                'user_id' => auth()->user()->id,
                'empresa_id' => auth()->user()->empresa_id,
                'status_id' => $request->status_id
            ]);
        }
        return BonusCartaoClienteDatabase::where('empresa_id', auth()->user()->empresa_id)->update([
            'bonus' => $request->bonus,
            'user_id' => auth()->user()->id,
            'status_id' => $request->status_id
        ]);
    }
    public function eliminarBonusCartaoCliente($bonusId){
        return BonusCartaoClienteDatabase::where('empresa_id', auth()->user()->empresa_id)
            ->where('id', $bonusId)->delete();
    }
}
