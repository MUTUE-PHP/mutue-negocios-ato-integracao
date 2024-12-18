<?php

namespace App\Http\Controllers\empresa\Faturacao;

use App\Application\UseCase\Empresa\Bancos\GetBancos;
use App\Application\UseCase\Empresa\Clientes\GetClientes;
use App\Application\UseCase\Empresa\Faturacao\EmitirDocumentoAeroporto;
use App\Application\UseCase\Empresa\Faturacao\EmitirDocumentoAeroportoCarga;
use App\Application\UseCase\Empresa\Faturacao\GetTipoDocumentoByFaturacao;
use App\Application\UseCase\Empresa\Faturacao\SimuladorFaturaCargaAeroporto;
use App\Application\UseCase\Empresa\FormasPagamento\GetFormasPagamentoByFaturacao;
use App\Application\UseCase\Empresa\mercadorias\GetTiposMercadorias;
use App\Application\UseCase\Empresa\Pais\GetPaises;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Application\UseCase\Empresa\Produtos\GetProdutoPeloCentroCustoId;
use App\Application\UseCase\Empresa\Produtos\GetProdutoPeloTipoServico;
use App\Application\UseCase\Empresa\Produtos\GetProdutos;
use App\Application\UseCase\Empresa\TiposServicos\GetTiposServicos;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaCarga;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Models\empresa\Moeda;
use App\Repositories\Empresa\FacturaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class EmissaoFaturaCargaController extends Component
{
    use LivewireAlert;
    use PrintFaturaCarga;

    public $clientes;
    public $bancos;
    public $moedas;
    public $empresa;
    public $item = [
        'produto' => null,
        'tipoMercadoriaId' => 1,
        'sujeitoDespachoId' => 1,
        'especificacaoMercadoriaId' => 1,
        'desconto' => 0
    ];
    public $fatura = [
        'cartaDePorte' => null,
        'tipoDocumento' => 3, //Fatura proforma
        'tipoOperacao' => 1, //Importação
        'tipoMercadoriaId' => 1,
        'formaPagamentoId' => null, //Fatura proforma
        'moedaPagamento' => 'AOA',
        'observacao' => null,
        'isencaoIVA' => false,
        'isencaoCargaTransito' => false,
        'retencao' => false,
        'taxaRetencao' => 0,
        'valorRetencao' => 0,
        'nomeProprietario' => null,
        'clienteId' => null,
        'nomeCliente' => null,
        'telefoneCliente' => null,
        'nifCliente' => null,
        'emailCliente' => null,
        'enderecoCliente' => null,
        'peso' => null,
        'dataEntrada' => null,
        'dataSaida' => null,
        'nDias' => null,
        'taxaIva' => 0,
        'cambioDia' => 0,
        'contraValor' => 0,
        'valorliquido' => 0,
        'valorDesconto' => 0,
        'valorIliquido' => 0,
        'valorImposto' => 0,
        'moeda' => null,
        'total' => 0,
        'items' => []
    ];
    public $tipoServicos;
    public $tipoMercadorias;
    public $servicos;
    public $paises;
    public $tiposDocumentos;
    public $formasPagamentos = [];
    public $especificaoMercadorias;
    protected $listeners = ['selectedItem'];


//    public function moneyInput($money){
//        $this->fatura['peso'] = $money;
//    }

    public function selectedItem($item)
    {
        if ($item['atributo'] == 'clienteId') {
            $this->updatedFaturaClienteId($item['valor']);
        }
        $this->fatura[$item['atributo']] = $item['valor'];
    }

    public function mount()
    {
        $moedaEstrageiraUsado = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $this->fatura['moeda'] = $moedaEstrageiraUsado->execute('moeda_estrageira_usada')->valor;

        $getClientes = new GetClientes(new DatabaseRepositoryFactory());
        $this->clientes = $getClientes->execute();

        $this->empresa = auth()->user()->empresa;

        $getBancos = new GetBancos(new DatabaseRepositoryFactory());
        $this->bancos = $getBancos->execute();

        $getTipoMercadorias = new GetTiposMercadorias(new DatabaseRepositoryFactory());
        $this->tipoMercadorias = $getTipoMercadorias->execute();

        $getTiposServicos = new GetTiposServicos(new DatabaseRepositoryFactory());
        $this->tipoServicos = $getTiposServicos->execute();


        $getProdutos = new GetProdutoPeloTipoServico(new DatabaseRepositoryFactory());
        $this->servicos = $getProdutos->execute(1);

        $getPaises = new GetPaises(new DatabaseRepositoryFactory());
        $this->paises = $getPaises->execute();

        $getTiposDocumentos = new GetTipoDocumentoByFaturacao(new DatabaseRepositoryFactory());
        $this->tiposDocumentos = $getTiposDocumentos->execute();
        $this->moedas = Moeda::get();
    }

    public function resetField()
    {
        $this->fatura = [
            'cartaDePorte' => null,
            'tipoDocumento' => 3, //Fatura Proforma
            'formaPagamentoId' => null, //Fatura proforma
            'moedaPagamento' => 'AOA',
            'tipoMercadoriaId' => 1,
            'tipoOperacao' => 1, //Importação
            'observacao' => null,
            'isencaoIVA' => false,
            'isencaoCargaTransito' => false,
            'retencao' => false,
            'taxaRetencao' => 0,
            'valorRetencao' => 0,
            'nomeProprietario' => null,
            'clienteId' => null,
            'nomeCliente' => null,
            'telefoneCliente' => null,
            'nifCliente' => null,
            'emailCliente' => null,
            'enderecoCliente' => null,
            'peso' => null,
            'dataEntrada' => null,
            'dataSaida' => null,
            'nDias' => null,
            'taxaIva' => 0,
            'cambioDia' => 0,
            'contraValor' => 0,
            'valorliquido' => 0,
            'valorDesconto' => 0,
            'valorIliquido' => 0,
            'valorImposto' => 0,
            'moeda' => null,
            'total' => 0,
            'items' => []
        ];
        $this->formasPagamentos = [];
    }
    public function render()
    {
        $this->especificaoMercadorias = DB::table('especificacao_mercadorias')->get();
        return view("empresa.facturacao.createAeroportoCarga");
    }

    public function updatedFaturaIsencaoIVA()
    {
        $this->fatura['taxaRetencao'] = 0;
        $this->fatura['valorRetencao'] = 0;
        $this->fatura['taxaIva'] = 0;
        $this->fatura['cambioDia'] = 0;
        $this->fatura['contraValor'] = 0;
        $this->fatura['valorliquido'] = 0;
        $this->fatura['valorDesconto'] = 0;
        $this->fatura['valorIliquido'] = 0;
        $this->fatura['valorImposto'] = 0;
        $this->fatura['moeda'] = null;
        $this->fatura['total'] = 0;
        $this->fatura['items'] = [];
    }

    /**
    public function updatedFaturaIsencaoCargaTransito()
    {
        $this->fatura['taxaRetencao'] = 0;
        $this->fatura['valorRetencao'] = 0;
        $this->fatura['taxaIva'] = 0;
        $this->fatura['cambioDia'] = 0;
        $this->fatura['contraValor'] = 0;
        $this->fatura['valorliquido'] = 0;
        $this->fatura['valorDesconto'] = 0;
        $this->fatura['valorIliquido'] = 0;
        $this->fatura['valorImposto'] = 0;
        $this->fatura['moeda'] = null;
        $this->fatura['total'] = 0;
        $this->fatura['items'] = [];

    }
     * */

    public function updatedFaturaRetencao()
    {
        $this->fatura['taxaRetencao'] = 0;
        $this->fatura['valorRetencao'] = 0;
        $this->fatura['taxaIva'] = 0;
        $this->fatura['cambioDia'] = 0;
        $this->fatura['contraValor'] = 0;
        $this->fatura['valorIliquido'] = 0;
        $this->fatura['valorImposto'] = 0;
        $this->fatura['moeda'] = null;
        $this->fatura['total'] = 0;
        $this->fatura['items'] = [];
    }

    public function updatedFaturaClienteId($clienteId)
    {
        $cliente = DB::table('clientes')->where('id', $clienteId)
            ->where('empresa_id', auth()->user()->empresa_id)->first();

        if($cliente){
            $this->fatura['clienteId'] = $cliente->id;
            $this->fatura['nomeCliente'] = $cliente->nome;
            $this->fatura['telefoneCliente'] = $cliente->telefone_cliente;
            $this->fatura['nifCliente'] = $cliente->nif;
            $this->fatura['emailCliente'] = $cliente->email;
            $this->fatura['enderecoCliente'] = $cliente->endereco;
        }

    }

    public function removeCart($item)
    {
        foreach ($this->fatura['items'] as $key => $itemCart) {
            if ($itemCart['produtoId'] == $item['produtoId']) {
                unset($this->fatura['items'][$key]);
            }
        }
        $this->calculadoraTotal();
    }

    public function dataErrada()
    {
        $dataEntrada = new \DateTime($this->fatura['dataEntrada']);
        $dataSaida = new \DateTime($this->fatura['dataSaida']);
        return $dataEntrada > $dataSaida;
    }

    public function addCart()
    {
        $rules = [
            'fatura.cartaDePorte' => 'required',
            'fatura.peso' => 'required',
            'fatura.dataEntrada' => 'required',
            'fatura.dataSaida' => 'required',
        ];
        $messages = [
            'fatura.cartaDePorte.required' => 'campo obrigatório',
            'fatura.peso.required' => 'campo obrigatório',
            'fatura.dataEntrada.required' => 'campo obrigatório',
            'fatura.dataSaida.required' => 'campo obrigatório',
        ];
        $this->validate($rules, $messages);


        $key = $this->isCart(json_decode($this->item['produto']));
        if ($key !== false) {
            $this->confirm('O serviço já foi adicionado', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        if (!$this->item['produto']) {
            $this->confirm('Seleciona o serviço', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

        if ($this->dataErrada()) {
            $this->confirm('A data de entrada não deve ser maior que data de saída', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        $produto = json_decode($this->item['produto']);
        $this->item['nomeProduto'] = $produto->designacao;
        $this->item['produtoId'] = $produto->id;
        $this->item['produto'] = $this->item['produto'];
        $this->fatura['items'][] = (array)$this->item;

        $this->calculadoraTotal();
    }

    public function updatedFaturaTipoDocumento($tipoDocumento)
    {
        if ($tipoDocumento == 1) {
            $this->fatura['formaPagamentoId'] = 1;
            $getFormaPagamentoByFaturacao = new GetFormasPagamentoByFaturacao(new DatabaseRepositoryFactory());
            $this->formasPagamentos = $getFormaPagamentoByFaturacao->execute();
        } else {
            $this->fatura['formaPagamentoId'] = null;
            $this->formasPagamentos = [];
        }
        $simuladorFaturaCarga = new SimuladorFaturaCargaAeroporto(new DatabaseRepositoryFactory());
        $fatura = $simuladorFaturaCarga->execute($this->fatura);
        $this->fatura = $this->conversorModelParaArray($fatura);
    }
    public function updatedItemDesconto($desconto){

        if(empty($desconto)){
            $this->item['desconto'] = null;
        }else if($desconto > 100 || $desconto < 0){
            $this->item['desconto'] = 0;
        }
    }

    public function updatedFaturaFormaPagamentoId($formaPagamentoId)
    {
        $this->fatura['formaPagamentoId'] = $formaPagamentoId;
        $simuladorFaturaCarga = new SimuladorFaturaCargaAeroporto(new DatabaseRepositoryFactory());
        $fatura = $simuladorFaturaCarga->execute($this->fatura);
        $this->fatura = $this->conversorModelParaArray($fatura);
    }
    public function calculadoraTotal()
    {
        $simuladorFaturaCarga = new SimuladorFaturaCargaAeroporto(new DatabaseRepositoryFactory());
        $fatura = $simuladorFaturaCarga->execute($this->fatura);
        $this->fatura = $this->conversorModelParaArray($fatura);
    }
    public function selecionarTipoOperacao($tipoOperacao){
        $this->fatura['tipoOperacao'] = $tipoOperacao;
    }
    private function isCart($item)
    {
        $items = collect($this->fatura['items']);
        $posicao = $items->search(function ($produto) use ($item) {
            return $produto['produtoId'] === $item->id;
        });
        return $posicao;
    }
    private function conversorModelParaArray(FaturaCarga $output)
    {
        $fatura = [
            'cartaDePorte' => $output->getCartaDePorte(),
            'tipoDocumento' =>(string) $output->getTipoDocumentoId(),
            'tipoOperacao' => (string) $output->getTipoOperacao(),
            'formaPagamentoId' => (int) $output->getFormaPagamentoId()??1,
            'isencaoIVA' =>(string) $output->getIsencaoIVA(),
            'isencaoCargaTransito' => (string) $output->getIsencaoCargaTransito(),
            'retencao' =>(string) $output->getRetencao(),
            'taxaRetencao' =>(string) $output->getTaxaRetencao(),
            'valorRetencao' =>(string) $output->getValorRetencao(),
            'clienteId' => (string) $output->getClienteId(),
            'nomeCliente' =>(string) $output->getNomeCliente(),
            'nomeProprietario' => $output->getNomeProprietario(),
            'telefoneCliente' => $output->getTelefone(),
            'nifCliente' => $output->getNifCliente(),
            'emailCliente' => $output->getEmailCliente(),
            'enderecoCliente' => $output->getEnderecoCliente(),
            'peso' => (string) $output->getPeso(),
            'dataEntrada' => $output->getDataEntrada(),
            'dataSaida' => $output->getDataSaida(),
            'nDias' =>(string) $output->getNDias(),
            'taxaIva' =>(string) $output->getTaxaIva(),
            'cambioDia' => $output->getCambioDia(),
            'contraValor' =>(string) $output->getContraValor(),
            'valorliquido' =>(string) $output->getValorLiquido(),
            'valorDesconto' =>(string) $output->getDesconto(),
            'valorIliquido' =>(string) $output->getValorIliquido(),
            'valorImposto' =>(string) $output->getValorImposto(),
            'moeda' => $output->getMoeda(),
            'moedaPagamento' => $output->getMoedaPagamento(),
            'observacao' => $output->getObservacao(),
            'total' =>(string) $output->getTotal(),
            "items" => []
        ];
        foreach ($output->getItems() as $item) {
            array_push($fatura['items'], [
                'produtoId' =>(string) $item->getProdutoId(),
                'nomeProduto' =>(string) $item->getNomeProduto(),
                'taxa' =>(string) $item->getTaxa(),
                'taxaIva' =>(string) $output->getTaxaIva(),
                'valorIva' =>(string) $item->getValorIva(),
                'nDias' =>(string) $item->getNDias(),
                'sujeitoDespachoId' =>(string) $item->getSujeitoDespachoId(),
                'tipoMercadoriaId' =>(string) $item->getTaxaTipoMercadoriaId(),
                'especificacaoMercadoriaId' =>(string) $item->getEspecificacaoMercadoriaId(),
                'desconto' =>(string) $item->getDesconto(),
                'valorDesconto' =>(string) $item->getValorDesconto(),
                'valorImposto' =>(string) $item->getImposto(),
                'total' =>(string) $item->getTotal(),
                'totalIva' =>(string) $item->getTotalIva()
            ]);
        }
        return $fatura;
    }

    public function hydrate()
    {
        $this->emit('select2');
    }


    public function emitirDocumento()
    {
        $rules = [
            'fatura.cartaDePorte' => 'required',
            'fatura.nomeProprietario' => 'required',
            'fatura.clienteId' => 'required',
            'fatura.peso' => 'required',
            'fatura.dataEntrada' => 'required',
            'fatura.dataSaida' => 'required',
        ];
        $messages = [
            'fatura.cartaDePorte.required' => 'campo obrigatório',
            'fatura.nomeProprietario.required' => 'campo obrigatório',
            'fatura.clienteId.required' => 'campo obrigatório',
            'fatura.peso.required' => 'campo obrigatório',
            'fatura.dataEntrada.required' => 'campo obrigatório',
            'fatura.dataSaida.required' => 'campo obrigatório',
        ];
        $this->validate($rules, $messages);


        if (count($this->fatura['items']) <= 0) {
            $this->confirm('Adiciona os serviços', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }
        if (!$this->fatura['clienteId']) {
            $this->confirm('Seleciona o cliente', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

        $emitirDocumento = new EmitirDocumentoAeroportoCarga(new DatabaseRepositoryFactory());
        $this->fatura['tipoMercadoria'] = $this->item['tipoMercadoriaId'];
        $faturaId = $emitirDocumento->execute(new Request($this->fatura));
        $this->printFaturaCarga($faturaId);
        $this->resetField();

    }

    public function updatedFaturaDataEntrada($dataEntrada)
    {
        if ($this->fatura['dataSaida']) {
            $dataSaida = $this->fatura['dataSaida'];
            $this->fatura['nDias'] = $this->diff($dataEntrada, $dataSaida);
        }
    }


    public function diff($dataEntrada, $dataSaida)
    {
        $data1 = new \DateTime($dataEntrada);
        $data2 = new \DateTime($dataSaida);
        $diferenca = $data1->diff($data2);
        return $diferenca->days <= 0 ? 1 : $diferenca->days;
    }

    public function updatedFaturaDataSaida($dataSaida)
    {
        if ($this->fatura['dataEntrada']) {
            $dataEntrada = $this->fatura['dataEntrada'];
            $this->fatura['nDias'] = $this->diff($dataEntrada, $dataSaida);
        }
    }
}
