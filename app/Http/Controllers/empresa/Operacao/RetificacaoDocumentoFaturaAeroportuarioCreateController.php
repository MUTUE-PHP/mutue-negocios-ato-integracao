<?php

namespace App\Http\Controllers\empresa\Operacao;

use App\Application\UseCase\Empresa\Bancos\GetBancos;
use App\Application\UseCase\Empresa\Clientes\GetClientes;
use App\Application\UseCase\Empresa\Faturacao\EmitirDocumentoAeroportoAeronave;
use App\Application\UseCase\Empresa\Faturacao\EmitirDocumentoAeroportoCarga;
use App\Application\UseCase\Empresa\Faturacao\GetTipoDocumentoByFaturacao;
use App\Application\UseCase\Empresa\Faturacao\SimuladorFaturaAeronauticoAeroporto;
use App\Application\UseCase\Empresa\Faturacao\SimuladorFaturaCargaAeroporto;
use App\Application\UseCase\Empresa\FormasPagamento\GetFormasPagamentoByFaturacao;
use App\Application\UseCase\Empresa\mercadorias\GetTiposMercadorias;
use App\Application\UseCase\Empresa\Operacao\EmitirNotaCreditoRetificacao;
use App\Application\UseCase\Empresa\Pais\GetPaises;
use App\Application\UseCase\Empresa\Parametros\GetParametroPeloLabelNoParametro;
use App\Application\UseCase\Empresa\Produtos\GetProdutoPeloTipoServico;
use App\Application\UseCase\Empresa\TiposServicos\GetTiposServicos;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaAeronautico;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaCarga;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaItemAeronautico;
use App\Domain\Entity\Empresa\FaturaAeroporto\FaturaItemCarga;
use App\Domain\Entity\Empresa\Taxa;
use App\Http\Controllers\empresa\ReportShowController;
use App\Infra\Factory\Empresa\DatabaseRepositoryFactory;
use App\Infra\Repository\Empresa\EspecificacaoMercadoriaRepository;
use App\Infra\Repository\Empresa\TaxaCargaAduaneiraRepository;
use App\Models\empresa\Factura as FaturaDatabase;
use App\Models\empresa\Moeda;
use App\Models\empresa\NotaCredito;
use App\Models\empresa\TaxaCargaAduaneira;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RetificacaoDocumentoFaturaAeroportuarioCreateController extends Component
{
    use LivewireAlert;
    use TraitPrintNotaCredito;

    public $numeracaoFatura = null;
    public $observacao = null;
    public $formasPagamentos = [];
    public $faturaId;
    public $tipoFatura;
    public $item = [
        'produto' => null,
        'sujeitoDespachoId' => 1,
        'desconto' => 0
    ];
    protected $listeners = ['selectedItem'];

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
        $this->fatura[$item['atributo']] = $item['valor'];
    }
    public function updatedItemDesconto($desconto){

        if(empty($desconto)){
            $this->item['desconto'] = null;
        }else if($desconto > 100 || $desconto < 0){
            $this->item['desconto'] = 0;
        }
    }
    public function mount($faturaId)
    {
        $this->setarDadosFatura($faturaId);
        $moedaEstrageiraUsado = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $this->fatura['moeda'] = $moedaEstrageiraUsado->execute('moeda_estrageira_usada')->valor;

        $getClientes = new GetClientes(new DatabaseRepositoryFactory());
        $this->clientes = $getClientes->execute();

        $this->empresa = auth()->user()->empresa;

        $getBancos = new GetBancos(new DatabaseRepositoryFactory());
        $this->bancos = $getBancos->execute();


        $getProdutos = new GetProdutoPeloTipoServico(new DatabaseRepositoryFactory());
        $this->servicos = $getProdutos->execute(2);

        $getPaises = new GetPaises(new DatabaseRepositoryFactory());
        $this->paises = $getPaises->execute();

        $getTiposDocumentos = new GetTipoDocumentoByFaturacao(new DatabaseRepositoryFactory());
        $this->tiposDocumentos = $getTiposDocumentos->execute();

        $this->moedas = Moeda::get();
    }

    public function render()
    {
        return view('empresa.operacao.documentosRetificacaoFaturaAeroportuarioCreate');
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

    public function addCart()
    {

        if (!$this->item['produto']) {
            $this->confirm('Seleciona o serviço', [
                'showConfirmButton' => false,
                'showCancelButton' => false,
                'icon' => 'warning'
            ]);
            return;
        }

        $produtoData = json_decode($this->item['produto']);
        $rules = [
            'fatura.tipoDeAeronave' => 'required',
            'fatura.pesoMaximoDescolagem' => 'required',
            'fatura.dataDeAterragem' => 'required',
            'fatura.dataDeDescolagem' => 'required',
            'fatura.horaDeAterragem' => 'required',
            'fatura.horaDeDescolagem' => 'required',
            'fatura.peso' => [function ($attr, $peso, $fail) use ($produtoData) {
                if ($produtoData->id == 7 && !$peso) {
                    $fail("campo obrigatório");
                }
            }],
            'fatura.horaExtra' => [function ($attr, $horaExtra, $fail) use ($produtoData) {
                if (($produtoData->id == 8 || $produtoData->id == 10) && !$horaExtra) {
                    $fail("campo obrigatório");
                }
            }],
        ];
        $messages = [
            'fatura.tipoDeAeronave.required' => 'campo obrigatório',
            'fatura.pesoMaximoDescolagem.required' => 'campo obrigatório',
            'fatura.dataDeAterragem.required' => 'campo obrigatório',
            'fatura.dataDeDescolagem.required' => 'campo obrigatório',
            'fatura.horaDeAterragem.required' => 'campo obrigatório',
            'fatura.horaDeDescolagem.required' => 'campo obrigatório',
        ];
        $this->validate($rules, $messages);

        $key = $this->isCart($produtoData);
        if ($key !== false) {
            $this->confirm('O serviço já foi adicionado', [
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
        $this->item['peso'] = $this->fatura['peso'] ? (double)$this->fatura['peso'] : 0;
        $this->fatura['items'][] = (array)$this->item;
        $this->calculadoraTotal();
    }

    public function calculadoraTotal()
    {
        $simuladorFaturaAeronautico = new SimuladorFaturaAeronauticoAeroporto(new DatabaseRepositoryFactory());
        $fatura = $simuladorFaturaAeronautico->execute($this->fatura);
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
        $this->calculadoraTotal();
    }
    public function updatedFaturaHoraDeAterragem(){
        $this->calculadoraTotal();
    }
    public function updatedFaturaIsencaoIVA(){
        $this->calculadoraTotal();
    }
    public function updatedFaturaRetencao(){
        $this->calculadoraTotal();
    }
    public function updatedFaturaHoraDeDescolagem(){
        $this->calculadoraTotal();
    }
    public function updatedFaturaDataDeDescolagem(){
        $this->calculadoraTotal();
    }
    public function updatedFaturaDataDeAterragem(){
        $this->calculadoraTotal();
    }
    public function updatedFaturaTipoDeAeronave(){
        $this->calculadoraTotal();
    }
    public function updatedObservacao($observacao){
        $this->fatura['observacao'] = $observacao;
    }

    public function setarDadosFatura($faturaId)
    {
        $fatura = FaturaDatabase::with(['facturas_items'])
            ->where('id', $faturaId)
            ->where('anulado', '!=', 'Y')
            ->where('retificado', '!=', 'Y')
            ->where('empresa_id', auth()->user()->empresa_id)
            ->first();

        if (!$fatura){
            $faturaRetificado = FaturaDatabase::with(['facturas_items'])
                ->where('id', $faturaId)
                ->where('retificado', '=', 'Y')
                ->where('empresa_id', auth()->user()->empresa_id)
                ->first();
            if($faturaRetificado->tipoFatura == 1){
                return $this->redirect('/empresa/facturas/cargas');
            }else if($faturaRetificado->tipoFatura == 2){
                return $this->redirect('/empresa/facturas/aeroportuario');
            }
            else if($faturaRetificado->tipoFatura == 3){
                return $this->redirect('/empresa/facturas/outros/servicos');
            }
            else if($faturaRetificado->tipoFatura == 4){
                return $this->redirect('/empresa/facturas/servico/comerciais');
            }


        }
        $this->numeracaoFatura = $fatura['numeracaoFactura'];
        $this->faturaId = $fatura['id'];
        $this->tipoFatura = $fatura['tipoFatura'];

        $getTaxaReaberturaComercial = new GetParametroPeloLabelNoParametro(new DatabaseRepositoryFactory());
        $taxaReaberturaComercial = (float)$getTaxaReaberturaComercial->execute('tarifa_reabertura_comercial')->valor;

        $faturaAeronautico = new FaturaAeronautico(
            $fatura->tipo_documento,
            $fatura->formaPagamentoId,
            $this->observacao,
            $fatura['isencaoIVA'] == 'Y' ? true : false,
            $fatura['taxaRetencao'] > 0 ? true : false,
            $fatura['valorRetencao'],
            $fatura['nomeProprietario'],
            $fatura['clienteId'],
            $fatura['nome_do_cliente'],
            $fatura['telefone_cliente'],
            $fatura['nif_cliente'],
            $fatura['email_cliente'],
            $fatura['endereco_cliente'],
            $fatura['tipoDeAeronave'],
            $fatura['pesoMaximoDescolagem'],
            $fatura['dataDeAterragem'],
            $fatura['dataDeDescolagem'],
            $fatura['horaDeAterragem'],
            $fatura['horaDeDescolagem'],
            $fatura['taxaIva'],
            $fatura['peso'],
            $fatura['horaExtra'],
            $fatura['cambioDia'],
            $fatura['moeda'],
            $fatura['moedaPagamento'],
            'SIM'
        );
        $peso = 0;
        foreach ($fatura->facturas_items as $item) {
            $item = (object)$item;
            if ($item->produtoId == 12 || $item->produtoId == 13) {
                $peso += $item->peso;
                $faturaAeronautico->setPeso($item->peso);
                $faturaAeronautico->setPesoTotal($peso);
            }
            if ($item->produtoId == 7) {
                $faturaAeronautico->setPesoTotal($item->peso);
            }
            if ($item->produtoId == 7 || $item->produtoId == 12 || $item->produtoId == 13) {
                $taxaAduaneira = $item->taxaAduaneiro;
                $sujeitoDespachoId = $item->sujeitoDespachoId;
            } else {
                $taxaAduaneira = 0;
                $sujeitoDespachoId = null;
            }
            $faturaItemAeronautico = new FaturaItemAeronautico(
                $item->produtoId,
                $item->nomeProduto,
                $item->desconto,
                $fatura['pesoMaximoDescolagem'],
                $faturaAeronautico->getHoraEstacionamento(),
                $item->taxa,
                $item->taxaLuminosa,
                $taxaAduaneira,
                $sujeitoDespachoId,
                $item->taxaIva,
                $item->peso,
                $fatura['horaExtra'],
                $item->horaAberturaAeroporto,
                $item->horaFechoAeroporto,
                $item->taxaAbertoAeroporto,
                $fatura['cambioDia'],
                $taxaReaberturaComercial
            );

            $faturaAeronautico->addItem($faturaItemAeronautico);
        }
        $this->fatura = $this->conversorModelParaArray($faturaAeronautico);
    }
    private function conversorModelParaArray(FaturaAeronautico $output)
    {
        $fatura = [
            'nomeProprietario' => $output->getProprietario(),
            'clienteId' => $output->getClienteId(),
            'observacao' => $output->getObservacao(),
            'nomeCliente' => $output->getNomeCliente(),
            'telefoneCliente' => $output->getTelefoneCliente(),
            'nifCliente' => $output->getNifCliente(),
            'emailCliente' => $output->getEmailCliente(),
            'enderecoCliente' => $output->getEnderecoCliente(),
            'tipoDeAeronave' => $output->getTipoDeAeronave(),
            'pesoMaximoDescolagem' => $output->getPesoMaximoDescolagem(),
            'dataDeAterragem' => $output->getDataDeAterragem(),
            'dataDeDescolagem' => $output->getDataDeDescolagem(),
            'horaDeAterragem' => $output->getHoraDeAterragem(), //11h40 UTC
            'horaDeDescolagem' => $output->getHoraDeDescolagem(), //13h57 UTC
            'peso' => $output->getPeso(),
            'pesoTotal' => $output->getPesoTotal(),
            'horaExtra' => $output->getHoraExtra(),
            'tipoDocumento' => $output->getTipoDocumento(), //Fatura recibo
            'formaPagamentoId' => $output->getFormaPagamentoId(),
            'isencaoIVA' => $output->getIsencaoIVA(),
            'retencao' => $output->getRetencao(),
            'taxaRetencao' => $output->getTaxaRetencao(),
            'valorRetencao' => $output->getValorRetencao(),
            'taxaIva' => $output->getTaxaIva(),
            'cambioDia' => $output->getCambioDia(),
            'contraValor' => $output->getContraValor(),
            'valorliquido' => $output->getValorLiquido(),
            'valorDesconto' => $output->getDesconto(),
            'valorIliquido' => $output->getValorIliquido(),
            'valorImposto' => $output->getValorImposto(),
            'total' => $output->getTotal(),
            'moeda' => $output->getMoeda(),
            'moedaPagamento' => $output->getMoedaPagamento(),
            'items' => []
        ];
        foreach ($output->getItems() as $item) {
            array_push($fatura['items'], [
                'produtoId' => $item->getProdutoId(),
                'quantidade' => 1,
                'nomeProduto' => $item->getNomeProduto(),
                'pmd' => $item->getPMD(),
                'valorIva' => $item->getValorIva(),
                'taxaIva' => $item->getTaxaIva(),
                'horaEstacionamento' => $item->getHoraEstacionamento(),
                'taxa' => $item->getTaxa(),
                'taxaLuminosa' => $item->getTaxaLuminosa(),
                'taxaAduaneiro' => $item->getTaxaAduaneiro(),
                'sujeitoDespachoId' => $item->getSujeitoDespachoId(),
                'peso' => $item->getPeso(),
                'horaExtra' => $item->getHoraExtra(),
                'taxaAbertoAeroporto' => $item->getTaxaAbertoAeroporto(),
                'cambioDia' => $item->getCambioDia(),
                'valorImposto' => $item->getImposto(),
                'desconto' => $item->getDesconto(),
                'valorDesconto' => $item->getValorDesconto(),
                'total' => $item->getTotal(),
                'totalIva' => $item->getTotalIva(),
                'horaAberturaAeroporto' => $item->getHoraAberturaAeroporto(),
                'horaFechoAeroporto' => $item->getHoraFechoAeroporto(),
            ]);
        }
        return $fatura;
    }
    public function emitirDocumento()
    {
        $rules = [
            'observacao' => 'required',
            'fatura.tipoDeAeronave' => 'required',
            'fatura.pesoMaximoDescolagem' => 'required',
            'fatura.dataDeAterragem' => 'required',
            'fatura.dataDeDescolagem' => 'required',
            'fatura.horaDeAterragem' => 'required',
            'fatura.horaDeDescolagem' => 'required',
        ];
        $messages = [
            'observacao.required' => 'campo obrigatório',
            'fatura.tipoDeAeronave.required' => 'campo obrigatório',
            'fatura.pesoMaximoDescolagem.required' => 'campo obrigatório',
            'fatura.dataDeAterragem.required' => 'campo obrigatório',
            'fatura.dataDeDescolagem.required' => 'campo obrigatório',
            'fatura.horaDeAterragem.required' => 'campo obrigatório',
            'fatura.horaDeDescolagem.required' => 'campo obrigatório',
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

        $emitirDocumento = new EmitirNotaCreditoRetificacao(new DatabaseRepositoryFactory());
        $this->fatura['facturaId'] = $this->faturaId;
        $this->fatura['tipoFatura'] = $this->tipoFatura;
        $notaCreditoId = $emitirDocumento->execute(new Request($this->fatura));
        $this->printNotaCredito($notaCreditoId);
    }

}

