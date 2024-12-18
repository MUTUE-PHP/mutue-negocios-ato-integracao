<div class="row">
    <div class="col-md-6">
        <h3>Total</h3>
        <div>
            <label><strong>Total Fatura: {{ number_format($fatura['totalPrecoFatura'],2,',','.') }}</strong></label>

        </div>
        <div>
            <label><strong>Total IVA: {{ number_format($fatura['totalIva'],2,',','.') }}</strong></label>
        </div>
        <div>
            <label><strong>Total Desconto: {{ number_format($fatura['totalDesconto'],2,',','.') }}</strong></label>
        </div>
        <div>
            <label><strong>Total Pagar: {{ number_format($fatura['totalPagar'],2,',','.') }}</strong></label>
        </div>

        <table class="table table-hover">
            <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>IVA</th>
                <th>Preco</th>
            </tr>
            </thead>
            <tbody>
            @foreach($fatura['items'] as $key=> $item)
            <tr>

                <td>{{ $item['nomeProduto'] }}</td>
                <td>{{ number_format($item['quantidade'], 1, ',', '.') }}</td>
                <td>{{ number_format($item['taxaIva'], 1, ',', '.') }}</td>
                <td>{{ number_format($item['precoVendaProduto'],2,',','.') }}</td>
                <td wire:click="removerItemCarrinho({{ json_encode($item) }}, {{$key}})"><i class="fa fa-close" style="font-size: 30px;color: red; cursor:pointer;"></i></td>
            </tr>
            @endforeach
            </tbody>
        </table>

    </div>
    <div class="">
        <div>
            <label>Armazens</label>
            <select wire:model="armazemId">
                @foreach($armazens as $armazem)
                <option value="{{ $armazem['id'] }}">{{ $armazem['designacao'] }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Forma de Pagamentos</label>
            <select wire:model="formaPagamentoId">
                @foreach($formasPagamento as $formaPagamento)
                    <option value="{{ $formaPagamento['id'] }}">{{ $formaPagamento['descricao'] }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Clientes</label>
            <select wire:model="formaPagamentoId">
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente['id'] }}">{{ $cliente['nome'] }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Desconto</label>
            <input wire:model="desconto"/>
        </div>

    </div>
    <div class="col-md-6">
        <div>
            <input wire:model="search" placeholder="buscar produto..." />
        </div>
        <div class="row" style="margin-top: 20px">

            @foreach($produtos as $key=> $produto)
                <div class="col-md-2" wire:click="adicionarCarrinho({{ json_encode($produto) }}, {{ $key }})" style="background: #3c4678;color: white;cursor: pointer; margin:5px">
                    <span style="    position: absolute;
    color: white;
    background: red;
    border-radius: 5px;
    right: 0;">{{ number_format($produto['quantidadeStock'], 1, ',', '.') }}</span>
                    <span>{{ \Illuminate\Support\Str::limit($produto['nomeProduto'], 15) }}</span><br>
                    <span>{{ number_format($produto['precoVendaProduto'],2,',','.') }}</span>
                </div>
            @endforeach

        </div>
    </div>
</div>

<style>
    .productItem {
        background: #a8a7a7;
        padding: 10px;
        margin-left: 5px;
    }

    .productItem:hover {
        background: #0d6aad;
        color: white;
        cursor: pointer;
    }

</style>
