<?php

namespace App\Infra\Repository\VendasOnline;

use App\Domain\Entity\VendasOnline\PagamentoItemVendaOnline;
use App\Domain\Entity\VendasOnline\PagamentoVendasOnline;
use App\Models\empresa\Factura;
use App\Models\empresa\PagamentoVendasOnlineItems;
use App\Models\empresa\PagamentoVendasOnline as PagamentoVendasOnlineDataBase;
use App\Models\empresa\Produto;
use Carbon\Carbon;
use Illuminate\Support\Str;
use NumberFormatter;

class PagamentoVendasOnlineRepository
{

    public function atualizarPagamento($pagamentoId, $statusPagamentoId, $comprovativoBancario){
        return PagamentoVendasOnlineDataBase::where('id', $pagamentoId)->update([
            'statusPagamentoId' => $statusPagamentoId,
            'comprovativoBancario' => $comprovativoBancario
        ]);
    }
    public function get($pagamentoId){
        return PagamentoVendasOnlineDataBase::where('id', $pagamentoId)->first();
    }
    public function confirmarEntregaProdutoVendaOnline($pagamentoId){
        return PagamentoVendasOnlineDataBase::where('id', $pagamentoId)->update([
            'statusPagamentoId' => 5
        ]);
    }

    public function salvar(PagamentoVendasOnline $pagamentoVendasOnline)
    {
        $taxaEntrega = 0;
        if($pagamentoVendasOnline->getTaxaCobrarTaxaEntrega()){
            $taxaEntrega = $pagamentoVendasOnline->getEntrega()->getInstanciaEntrega()->getTaxaEntrega();
        }
        return PagamentoVendasOnlineDataBase::create([
            'uuid' => Str::uuid(),
            'codigo' => $pagamentoVendasOnline->getSequencia(),
            'bancoId' => $pagamentoVendasOnline->getBancoId(),
            'dataPagamentoBanco' => $pagamentoVendasOnline->getDataPagamentoBanco(),
            'totalPagamento' => $pagamentoVendasOnline->getTotalPagamento(),
            'totalDesconto' => $pagamentoVendasOnline->getTotalDesconto(),
            'totalIva' => $pagamentoVendasOnline->getTotalIva(),
            'comprovativoBancario' => $pagamentoVendasOnline->getComprovativoBancario(),
            'formaPagamentoId' => $pagamentoVendasOnline->getFormaPagamentoId(),
            'userId' => $pagamentoVendasOnline->getUserId(),
            'nomeUser' => auth()->user()->name??'Desconhecido',
            'tipoEntregaId' => $pagamentoVendasOnline->getEntrega()->getTipoEntrega(),
            'taxaEntrega' =>  $taxaEntrega,
            'estimativaEntrega' => Carbon::now()->addDay($pagamentoVendasOnline->getEstimativaEntrega()),
            'statusPagamentoId' => $pagamentoVendasOnline->getStatusPagamentoId(),
            'nomeUserEntrega' => $pagamentoVendasOnline->getEnderecoEntrega()->getNomeUserEntrega(),
            'apelidoUserEntrega' => $pagamentoVendasOnline->getEnderecoEntrega()->getApelidoUserEntrega(),
            'enderecoEntrega' => $pagamentoVendasOnline->getEnderecoEntrega()->getEndereco(),
            'pontoReferenciaEntrega' => $pagamentoVendasOnline->getEnderecoEntrega()->getPontoReferencia(),
            'telefoneUserEntrega' => $pagamentoVendasOnline->getEnderecoEntrega()->getTelefoneUser(),
            'comunaId' => $pagamentoVendasOnline->getEnderecoEntrega()->getComunaId(),
            'emailEntrega' => $pagamentoVendasOnline->getEnderecoEntrega()->getEmail(),
            'numeroCartaoCliente' => $pagamentoVendasOnline->getNumeroCartaoCliente(),
            'observacaoEntrega' => $pagamentoVendasOnline->getEnderecoEntrega()->getObservacao(),
            'empresaId' => auth()->user()->empresa_id
        ]);
    }
    public function validarPagamento($pagamento){
        return PagamentoVendasOnlineDataBase::where('id', $pagamento->id)->update([
            'statusPagamentoId' => 1,
            'operadorId' => auth()->user()->id
        ]);
    }
    public function rejeitarPagamento($pagamento){
        return PagamentoVendasOnlineDataBase::where('id', $pagamento->id)->update([
            'statusPagamentoId' => 3,
            'motivoRejeicao' => $pagamento->motivoRejeicao
        ]);
    }
    public function getSequenciaPagamento(){
        return PagamentoVendasOnlineDataBase::where('empresaId', auth()->user()->empresa_id)
        ->orderBy('id','desc')->count() + 1;
    }
    public function salvarItemPagamento(PagamentoItemVendaOnline $pagamentoItem, $pagamentoId){
        return PagamentoVendasOnlineItems::create([
            'uuid' => Str::uuid(),
            'produtoId' => $pagamentoItem->getProdutoId(),
            'precoVendaProduto' => $pagamentoItem->getPrecoVendaProduto(),
            'nomeProduto' => $pagamentoItem->getNomeProduto(),
            'quantidade' => $pagamentoItem->getQuantidade(),
            'pagamentoVendasOnlineId' => $pagamentoId,
            'taxaIvaValor' => $pagamentoItem->getTaxaValor(),
            'taxaIva' => $pagamentoItem->getTaxa(),
            'subtotal' => $pagamentoItem->getSubtotal(),
        ]);
    }

    public function getRouteFaturaRecibo($faturaId)
    {

    }

    public function getRotaPagamentoVendaOnline($pagamentoId)
    {

    }

    public function getFaturaRecibo($pagamentoId)
    {
        return Factura::where('pagamento_venda_online_id', $pagamentoId)->first();
    }
    public function listarPagamentosRejeitadoUtilizadorEspecificoAutenticado($search){
        $f = new NumberFormatter("pt", NumberFormatter::SPELLOUT);
        $pagamentos = PagamentoVendasOnlineDataBase::with(['pagamentoVendasOnlineItems', 'statu'])
            ->where('userId', auth()->user()->id)
            ->where('statusPagamentoId', 3)
            ->paginate();
        $env = env('APP_URL');
        foreach ($pagamentos as $key => $pagamento) {

            $fatura = $this->getFaturaRecibo($pagamento['id']);
            $pagamentos[$key]['urlComprovativo'] = env('APP_URL') . 'upload/' . $pagamento['comprovativoBancario'];
            $pagamentos[$key]['comprovativoPagamento'] = isset($fatura['id']) ? $env . 'api/empresa/imprimir/factura/'.$fatura['id'] : $env.'api/portal/pagamentosVendaOnline/imprimir/' . $pagamento['id'];
            $pagamentos[$key]['total_extenso'] =  $f->format($pagamento['totalPagamento'] ?? 0);
            foreach ($pagamento['pagamentoVendasOnlineItems'] as $key => $item) {
                $produto = Produto::find($item['produtoId']);
                $pagamento['pagamentoVendasOnlineItems'][$key]['imagem_produto'] = $produto['imagem_produto'];
            }
        }
        return $pagamentos;
    }
    public function listarPagamentosUtilizadorEspecificoAutenticado($search)
    {
        $f = new NumberFormatter("pt", NumberFormatter::SPELLOUT);
        $pagamentos = PagamentoVendasOnlineDataBase::with(['pagamentoVendasOnlineItems', 'statu'])
            ->where('userId', auth()->user()->id)
            ->paginate();
        $env = env('APP_URL');
        foreach ($pagamentos as $key => $pagamento) {

            $fatura = $this->getFaturaRecibo($pagamento['id']);
            $pagamentos[$key]['urlComprovativo'] = env('APP_URL') . 'upload/' . $pagamento['comprovativoBancario'];
            $pagamentos[$key]['comprovativoPagamento'] = isset($fatura['id']) ? $env . 'api/empresa/imprimir/factura/'.$fatura['id'] : $env.'api/portal/pagamentosVendaOnline/imprimir/' . $pagamento['id'];
            $pagamentos[$key]['total_extenso'] =  $f->format($pagamento['totalPagamento'] ?? 0);
            foreach ($pagamento['pagamentoVendasOnlineItems'] as $key => $item) {
                $produto = Produto::find($item['produtoId']);
                $pagamento['pagamentoVendasOnlineItems'][$key]['imagem_produto'] = $produto['imagem_produto'];
            }
        }
        return $pagamentos;
    }
    public function getPagamentoVendaOnline($pagamentoId){
        return PagamentoVendasOnlineDataBase::with(['pagamentoVendasOnlineItems','user', 'statu', 'banco'])
            ->where('id', $pagamentoId)
            ->first();
    }
    public function listarTodosPagamentosVendaOnline($filtro){
        return PagamentoVendasOnlineDataBase::with(['comuna','tipoEntrega', 'comuna.municipio'])->orderBy('id', $filtro['orderBy'])->with(['pagamentoVendasOnlineItems', 'statu', 'banco'])
            ->filter($filtro)
            ->search(trim($filtro['search']))
            ->paginate();
    }
}
