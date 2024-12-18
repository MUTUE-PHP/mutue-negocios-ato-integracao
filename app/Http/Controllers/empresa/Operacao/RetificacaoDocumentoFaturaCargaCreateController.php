<?php

namespace App\Http\Controllers\empresa\Operacao;

use App\Application\UseCase\Empresa\Bancos\GetBancos;
use App\Application\UseCase\Empresa\Clientes\GetClientes;
use App\Application\UseCase\Empresa\Faturacao\EmitirDocumentoAeroportoCarga;
use App\Application\UseCase\Empresa\Faturacao\GetTipoDocumentoByFaturacao;
use App\Application\UseCase\Empresa\Faturacao\SimuladorFaturaCargaAeroporto;
use App\Application\UseCase\Empresa\FormasPagamento\GetFormasPagamentoByFaturacao;
use App\Application\UseCase\Empresa\mercadorias\GetTiposMercadorias;
use App\Application\UseCase\Empresa\Operacao\EmitirNotaCreditoRetificacao;
use App\Application\UseCase\Empresa\Pais\GetPaises;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Application\UseCase\Empresa\Produtos\GetProdutoPeloTipoServico;
use App\Application\UseCase\Empresa\TiposServicos\GetTiposServicos;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaCarga;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaItemCarga;
use App\Domain\Entity\Empresa\Taxa;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\EspecificacaoMercadoriaRepository;
use App\Infra\Repository\Empresa\TaxaCargaAduaneiraRepository;
use App\Models\empresa\Factura as FaturaDatabase;
use App\Models\empresa\Moeda;
use App\Models\empresa\NotaCredito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RetificacaoDocumentoFaturaCargaCreateController extends Component
{
    use LivewireAlert;
    use TraitPrintNotaCredito;

    public $faturaId;
    public $tipoFatura;
    public $numeracaoFatura;
    public $observacao = null;

    public $item = [
        'produto' => null,
        'tipoMercadoriaId' => 1,
        'sujeitoDespachoId' => 1,
        'especificacaoMercadoriaId' => 1,
        'desconto' => 0
    ];
    public $tipoServicos;
    public $tipoMercadorias;
    public $servicos;
    public $paises;
    public $tiposDocumentos;
    public $formasPagamentos = [];
    public $especificaoMercadorias;
    protected $listeners = ['selectedItem', 'moneyInput'];

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

    public function selectedItem($item)
    {
        if ($item['atributo'] == 'clienteId') {
            $this->updatedFaturaClienteId($item['valor']);
        }
        $this->fatura[$item['atributo']] = $item['valor'];
    }

    public function mount($id)
    {
        $this->setarDadosFatura($id);
        $moedaEstrageiraUsado = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $this->fatura['moeda'] = $moedaEstrageiraUsado->execute('moeda_estrageira_usada')->valor;

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

    public function render()
    {
        $this->especificaoMercadorias = DB::table('especificacao_mercadorias')->get();
        return view('empresa.operacao.documentosRetificacaoFaturaCargaCreate');
    }

    public function updatedFaturaRetencao()
    {
        $this->calculadoraTotal();

    }
    public function updatedObservacao($observacao){
        $this->fatura['observacao'] = $observacao;
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

    public function updatedFaturaIsencaoIVA()
    {
        $this->calculadoraTotal();
    }

    public function updatedFaturaIsencaoCargaTransito()
    {
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

    public function updatedItemDesconto($desconto)
    {
        if (empty($desconto)) {
            $this->item['desconto'] = null;
        } else if ($desconto > 100 || $desconto < 0) {
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

    private function isCart($item)
    {
        $items = collect($this->fatura['items']);
        $posicao = $items->search(function ($produto) use ($item) {
            return $produto['produtoId'] === $item->id;
        });
        return $posicao;
    }

    public function updatedFaturaDataEntrada($dataEntrada)
    {
        if ($this->fatura['dataSaida']) {
            $dataSaida = $this->fatura['dataSaida'];
            $this->fatura['nDias'] = $this->diff($dataEntrada, $dataSaida);
            $this->calculadoraTotal();
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
            $this->calculadoraTotal();
        }
    }

    public function setarDadosFatura($faturaId)
    {
        $fatura = FaturaDatabase::with(['facturas_items'])
            ->where('id', $faturaId)
            ->where('anulado', '!=', 'Y')
            ->where('retificado', '!=', 'Y')
            ->where('empresa_id', auth()->user()->empresa_id)
            ->first();

        if (!$fatura) return redirect()->route('facturas.index');
        $this->numeracaoFatura = $fatura['numeracaoFactura'];
        $this->tipoFatura = $fatura['tipoFatura'];
        $this->faturaId = $fatura['id'];
        $faturaCarga = new FaturaCarga(
            $fatura['cartaDePorte'],
            $fatura['tipo_documento'],
            $fatura['tipoOperacao'],
            $fatura['formaPagamentoId'],
            $fatura['isencaoIVA'] == 'Y' ? true : false,
            $fatura['isencao24hCargaTransito'] == 'Y' ? true : false,
            $fatura['taxaRetencao'] > 0 ? true : false,
            $fatura['valorRetencao'],
            $fatura['clienteId'],
            $fatura['nome_do_cliente'],
            $fatura['nomeProprietario'],
            $fatura['telefone_cliente'],
            $fatura['nif_cliente'],
            $fatura['email_cliente'],
            $fatura['endereco_cliente'],
            $fatura['peso'],
            $fatura['dataEntrada'],
            $fatura['dataSaida'],
            $fatura['nDias'],
            $fatura['taxaIva'],
            $fatura['cambioDia'],
            $fatura['moeda'],
            $fatura['moedaPagamento'],
            $this->observacao,
        );


        foreach ($fatura->facturas_items as $item) {
            $item = (object)$item;
            if ($item->produtoId == 1) {//Produto/Serviçso do tipo Carga
                $taxaCargaAduaneira = DB::table('taxa_carga_aduaneira')->where('id', $item->sujeitoDespachoId)->first();
                $taxa = new Taxa(
                    $taxaCargaAduaneira->designacao,
                    $taxaCargaAduaneira->taxa,
                    0
                );
            } else {// Todos Produtos/Serviçso diferentes de Carga
                $taxaTipoMercadoria = DB::table('tipos_mercadoria')->where('id', $item->tipoMercadoriaId)->first();
                $espeficificaoMercadoria = DB::table('especificacao_mercadorias')->where('id', $item->especificacaoMercadoriaId)->first();
                $desconto = $espeficificaoMercadoria->desconto;
                if ($item->produtoId == 3) { //Produto/Serviçso do tipo Manuseamento
                    $desconto = 0;
                }
                $taxa = new Taxa(
                    $taxaTipoMercadoria->designacao,
                    $taxaTipoMercadoria->taxa,
                    $desconto
                );
            }
            $faturaItemCarga = new FaturaItemCarga(
                $item->produtoId,
                $item->nomeProduto,
                $item->desconto,
                $taxa,
                $item->taxaIva,
                $fatura['peso'],
                $item->nDias,
                $fatura['cambioDia'],
                $item->sujeitoDespachoId,
                $item->tipoMercadoriaId,
                $item->especificacaoMercadoriaId,
                $fatura['dataEntrada'],
                $fatura['dataSaida'],
                $fatura['tipoOperacao'],
                $fatura['isencao24hCargaTransito'] == 'Y' ? true : false,
            );
            $faturaCarga->addItem($faturaItemCarga);
        }
        $this->fatura = $this->conversorModelParaArray($faturaCarga);
    }

    public function emitirDocumento()
    {
        $rules = [
            'observacao' => 'required',
            'fatura.cartaDePorte' => 'required',
            'fatura.peso' => 'required',
            'fatura.dataEntrada' => 'required',
            'fatura.dataSaida' => 'required',
        ];
        $messages = [
            'observacao.required' => 'campo obrigatório',
            'fatura.cartaDePorte.required' => 'campo obrigatório',
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
        $emitirDocumento = new EmitirNotaCreditoRetificacao(new DatabaseRepositoryFactory());
        $this->fatura['tipoMercadoria'] = $this->item['tipoMercadoriaId'];
        $this->fatura['tipoFatura'] = $this->tipoFatura;
        $this->fatura['faturaId'] = $this->faturaId;
        $notaCreditoId = $emitirDocumento->execute(new Request($this->fatura));
        $this->printNotaCredito($notaCreditoId);
    }

    private function conversorModelParaArray(FaturaCarga $output)
    {
        $fatura = [
            'cartaDePorte' => $output->getCartaDePorte(),
            'tipoDocumento' => $output->getTipoDocumentoId(),
            'tipoOperacao' => $output->getTipoOperacao(),
            'formaPagamentoId' => $output->getFormaPagamentoId(),
            'isencaoIVA' => $output->getIsencaoIVA(),
            'isencaoCargaTransito' => $output->getIsencaoCargaTransito(),
            'retencao' => $output->getRetencao(),
            'taxaRetencao' => $output->getTaxaRetencao(),
            'valorRetencao' => $output->getValorRetencao(),
            'clienteId' => $output->getClienteId(),
            'nomeCliente' => $output->getNomeCliente(),
            'nomeProprietario' => $output->getNomeProprietario(),
            'telefoneCliente' => $output->getTelefone(),
            'nifCliente' => $output->getNifCliente(),
            'emailCliente' => $output->getEmailCliente(),
            'enderecoCliente' => $output->getEnderecoCliente(),
            'peso' => $output->getPeso(),
            'dataEntrada' => $output->getDataEntrada(),
            'dataSaida' => $output->getDataSaida(),
            'nDias' => $output->getNDias(),
            'taxaIva' => $output->getTaxaIva(),
            'cambioDia' => $output->getCambioDia(),
            'contraValor' => $output->getContraValor(),
            'valorliquido' => $output->getValorLiquido(),
            'valorDesconto' => $output->getDesconto(),
            'valorIliquido' => $output->getValorIliquido(),
            'valorImposto' => $output->getValorImposto(),
            'moeda' => $output->getMoeda(),
            'moedaPagamento' => $output->getMoedaPagamento(),
            'observacao' => $output->getObservacao(),
            'total' => $output->getTotal(),
            "items" => []
        ];
        foreach ($output->getItems() as $item) {
            array_push($fatura['items'], [
                'produtoId' => $item->getProdutoId(),
                'nomeProduto' => $item->getNomeProduto(),
                'taxa' => $item->getTaxa(),
                'taxaIva' => $output->getTaxaIva(),
                'valorIva' => $item->getValorIva(),
                'nDias' => $item->getNDias(),
                'sujeitoDespachoId' => $item->getSujeitoDespachoId(),
                'tipoMercadoriaId' => $item->getTaxaTipoMercadoriaId(),
                'especificacaoMercadoriaId' => $item->getEspecificacaoMercadoriaId(),
                'desconto' => $item->getDesconto(),
                'valorDesconto' => $item->getValorDesconto(),
                'valorImposto' => $item->getImposto(),
                'total' => $item->getTotal(),
                'totalIva' => $item->getTotalIva()
            ]);
        }
        return $fatura;
    }
}

