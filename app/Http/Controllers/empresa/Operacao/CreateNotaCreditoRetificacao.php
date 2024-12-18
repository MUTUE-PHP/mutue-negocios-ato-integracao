<?php

namespace App\Http\Controllers\empresa\Operacao;

use App\Models\empresa\NotaCredito;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait CreateNotaCreditoRetificacao
{

    public function createNotaCredito($data, $anulado = false)
    {
        try {
            DB::beginTransaction();
            $notaCredito = NotaCredito::create($data);
            DB::table('notas_creditos')->where('id', $notaCredito['id'])->update([
                'codigoBarra' => $this->getCodigoBarra($notaCredito['id'], $data['clienteId'])
            ]);

            if ($anulado) {
                DB::table('facturas')->where('id', $data['faturaId'])->update([
                    'anulado' => 'Y'
                ]);
            } else {
                DB::table('facturas')->where('id', $data['faturaId'])->update([
                    'retificado' => 'Y'
                ]);
            }
            foreach ($data['items'] as $item) {
                $item = (object)$item;
                DB::table('nota_credito_items')->insert([
                    'produtoId' => $item->produtoId,
                    'quantidade' => $item->quantidade,
                    'nomeProduto' => $item->nomeProduto,
                    'taxa' => $item->taxa,
                    'valorIva' => $item->valorIva,
                    'taxaIva' => $item->taxaIva,
                    'nDias' => $item->nDias ?? null,
                    'qtdMeses' => $item->qtdMeses ?? null,
                    'taxaImpostoPredial' => $item->impostoPredial ?? 0,
                    'taxaImpostoPredial' => $item->taxaImpostoPredial ?? 0,
                    'valorImpostoPredial' => $item->valorImpostoPredial ?? 0,
                    'unidadeMetrica' => $item->unidadeMetrica ?? null,
                    'sujeitoDespachoId' => $item->sujeitoDespachoId ?? null,
                    'tipoMercadoriaId' => $item->tipoMercadoriaId ?? 1,
                    'especificacaoMercadoriaId' => $item->especificacaoMercadoriaId ?? 1,
                    'desconto' => $item->desconto ?? 0,
                    'valorDesconto' => $item->valorDesconto ?? 0,
                    'valorImposto' => $item->valorImposto ?? 0,
                    'total' => $item->total ?? 0,
                    'totalIva' => $item->totalIva ?? 0,
                    'notaCreditoId' => $notaCredito['id'],
                ]);
            }
            DB::commit();
            return $notaCredito['id'];
        }catch (\Exception $exception){
            DB::rollBack();
        }
    }
    public function createNotaCreditoOutroServico($data, $anulado = false){

        try {
            DB::beginTransaction();
            $notaCreditoId = DB::table('notas_creditos')->insertGetId([
                'texto_hash' => $data['texto_hash'],
                'codigo_moeda' => 1,
                'clienteId' => $data->clienteId,
                'facturaId' => $data->facturaId,
                'nome_do_cliente' => $data->nomeCliente,
                'nomeProprietario' => $data->nomeProprietario,
                'telefone_cliente' => $data->telefoneCliente,
                'nif_cliente' => $data->nifCliente,
                'email_cliente' => $data->emailCliente,
                'endereco_cliente' => $data->enderecoCliente,
                'tipo_documento' => $data->tipoDocumento,
                'tipoDocumento' => $data->tipoDocumento,
                'numSequencia' => $data->numSequencia,
                'numDoc' => $data->numDoc,
                'hashValor' => $data->hashValor,
                'cliente_id' => $data->clienteId,
                'empresa_id' => auth()->user()->empresa_id,
                'centroCustoId' => session()->get('centroCustoId'),
                'user_id' => auth()->user()->id,
                'operador' => auth()->user()->name,
                'paisOrigemId' => 1,
                'tipoDeAeronave' => $data->numeroAeronave,
                'formaPagamentoId' => $data->formaPagamentoId,
                'isencaoIVA' => $data->isencaoIVA ? 'Y' : 'N',
                'taxaRetencao' => $data->taxaRetencao,
                'valorRetencao' => $data->valorRetencao,
                'taxaIva' => $data->taxaIva,
                'cambioDia' => $data->cambioDia,
                'contraValor' => $data->contraValor,
                'contraValor' => $data->contraValor,
                'valorliquido' => $data->valorliquido,
                'totalDesconto' => $data->valorDesconto,
                'valorIliquido' => $data->valorIliquido,
                'valorIliquido' => $data->valorIliquido,
                'valorImposto' => $data->valorImposto,
                'total' => $data->total,
                'moeda' => $data->moeda,
                'moedaPagamento' => $data->moedaPagamento,
                'tipoFatura' => $data->tipoFatura,
                'observacao' => $data->observacao,
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at']
            ]);
            //Gerar o codigo de barra
            DB::table('notas_creditos')->where('id', $notaCreditoId)->update([
                'codigoBarra' => $this->getCodigoBarra($notaCreditoId, $data->clienteId)
            ]);

            if ($anulado) {
                DB::table('facturas')->where('id', $data->facturaId)->update([
                    'anulado' => 'Y'
                ]);
            } else {
                DB::table('facturas')->where('id', $data->facturaId)->update([
                    'retificado' => 'Y'
                ]);
            }
            foreach ($data->items as $item) {
                $item = (object)$item;
                DB::table('nota_credito_items')->insert([
                    'produtoId' => $item->produtoId,
                    'quantidade' => $item->quantidade,
                    'nomeProduto' => $item->nomeProduto,
                    'taxa' => $item->taxa,
                    'valorIva' => $item->valorIva,
                    'taxaIva' => $item->taxaIva,
                    'desconto' => $item->desconto,
                    'valorDesconto' => $item->valorDesconto,
                    'total' => $item->total,
                    'totalIva' => $item->totalIva,
                    'notaCreditoId' => $notaCreditoId
                ]);
            }
            DB::commit();
            return $notaCreditoId;
        }catch (\Error $e){
            DB::rollBack();
        }
    }
    public function createNotaCreditoServicoComercial($data, $anulado = false){

        try {
            DB::beginTransaction();
            $notaCreditoId = DB::table('notas_creditos')->insertGetId([
                'texto_hash' => $data['texto_hash'],
                'codigo_moeda' => 1,
                'tipo_documento' => $data->tipoDocumento,
                'tipoDocumento' => $data->tipoDocumento,
                'formaPagamentoId' => $data->formaPagamentoId,
                'observacao' => $data->observacao,
                'isencaoIVA' => $data->isencaoIVA ? 'Y' : 'N',
                'isencaoOcupacao' => $data->isencaoOcupacao ? 'Y' : 'N',
                'taxaRetencao' => $data->taxaRetencao,
                'valorRetencao' => $data->valorRetencao,
                'numSequencia' => $data->numSequencia,
                'numDoc' => $data->numDoc,
                'hashValor' => $data->hashValor,
                'empresa_id' => auth()->user()->empresa_id,
                'centroCustoId' => session()->get('centroCustoId'),
                'user_id' => auth()->user()->id,
                'operador' => auth()->user()->name,
                'clienteId' => $data->clienteId,
                'cliente_id' => $data->clienteId,
                'facturaId' => $data->facturaId,
                'nome_do_cliente' => $data->nomeCliente,
                'nomeProprietario' => $data->nomeProprietario,
                'telefone_cliente' => $data->telefoneCliente,
                'nif_cliente' => $data->nifCliente,
                'email_cliente' => $data->emailCliente,
                'endereco_cliente' => $data->enderecoCliente,
                'taxaIva' => $data->taxaIva,
                'cambioDia' => $data->cambioDia,
                'valorConsumo' => $data->valorConsumo,
                'contraValor' => $data->contraValor,
                'valorIliquido' => $data->valorIliquido,
                'valorliquido' => $data->valorliquido,
                'valorImposto' => $data->valorImposto,
                'taxaImpostoPredial' => $data->taxaImpostoPredial,
                'valorImpostoPredial' => $data->valorImpostoPredial,
                'totalDesconto' => $data->totalDesconto,
                'tipoFatura' => 4,
                'total' => $data->total,
                'moeda' => $data->moeda,
                'moedaPagamento' => $data->moedaPagamento,
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at']
            ]);
            //Gerar o codigo de barra
            DB::table('notas_creditos')->where('id', $notaCreditoId)->update([
                'codigoBarra' => $this->getCodigoBarra($notaCreditoId, $data->clienteId)
            ]);

            if ($anulado) {
                DB::table('facturas')->where('id', $data->facturaId)->update([
                    'anulado' => 'Y'
                ]);
            } else {
                DB::table('facturas')->where('id', $data->facturaId)->update([
                    'retificado' => 'Y'
                ]);
            }
            foreach ($data->items as $item) {
                $item = (object)$item;
                DB::table('nota_credito_items')->insert([
                    'produtoId' => $item->produtoId,
                    'nomeProduto' => $item->nomeProduto,
                    'quantidade' => $item->quantidade,
                    'taxa' => $item->taxa,
                    'considera1hDepois30min' => $item->considera1hDepois30min,
                    'unidadeMetrica' => $item->unidadeMetrica,
                    'desconto' => $item->desconto,
                    'addArCondicionado' => $item->addArCondicionado ? 'Y' : 'N',
                    'qtdMeses' => $item->qtdMeses,
                    'total' => $item->total,
                    'valorConsumo' => $item->valorConsumo,
                    'totalIva' => $item->totalIva,
                    'taxaIva' => $item->taxaIva,
                    'taxaImpostoPredial' => $data->taxaImpostoPredial,
                    'valorImpostoPredial' => $data->valorImpostoPredial,
                    'valorIva' => $item->valorIva,
                    'notaCreditoId' => $notaCreditoId,
                    'descHoraEstacionamento' => $item->descHoraEstacionamento,
                    'dataEntrada' => $item->dataEntradaEstacionamento,
                    'dataSaida' => $item->dataSaidaEstacionamento
                ]);
            }
            DB::commit();
            return $notaCreditoId;
        }catch (\Error $e){
            DB::rollBack();
        }
    }
    public function createNotaCreditoCarga($data, $anulado = false)
    {
        try {
            DB::beginTransaction();
            $notaCreditoId = DB::table('notas_creditos')->insertGetId([
                'texto_hash' => $data['texto_hash'],
                'clienteId' => $data->clienteId,
                'facturaId' => $data->faturaId,
                'nome_do_cliente' => $data->nomeCliente,
                'tipo_documento' => $data->tipoDocumento,
                'tipoDocumento' => $data->tipoDocumento,
                'tipoMercadoria' => $data->tipoMercadoria,
                'tipoOperacao' => $data->tipoOperacao,
                'formaPagamentoId' => $data->formaPagamentoId,
                'isencaoIVA' => $data->isencaoIVA ? 'Y' : 'N',
                'isencao24hCargaTransito' => $data->isencaoCargaTransito,
                'taxaRetencao' => $data->taxaRetencao,
                'valorRetencao' => $data->valorRetencao,
                'numSequencia' => $data->numSequencia,
                'numDoc' => str_replace("PP", "FP", $data->numDoc),
                'hashValor' => $data->hashValor,
                'empresa_id' => auth()->user()->empresa_id,
                'centroCustoId' => session()->get('centroCustoId'),
                'user_id' => auth()->user()->id,
                'operador' => auth()->user()->name,
                'cartaDePorte' => $data->cartaDePorte,
                'peso' => $data->peso,
                'dataEntrada' => $data->dataEntrada,
                'dataSaida' => $data->dataSaida,
                'nDias' => $data->nDias,
                'taxaIva' => $data->taxaIva,
                'cambioDia' => $data->cambioDia,
                'moeda' => $data->moeda,
                'moedaPagamento' => $data->moedaPagamento,
                'contraValor' => $data->contraValor,
                'valorliquido' => $data->valorliquido,
                'totalDesconto' => $data->valorDesconto,
                'valorIliquido' => $data->valorIliquido,
                'valorImposto' => $data->valorImposto,
                'total' => $data->total,
                'nomeProprietario' => $data->nomeProprietario,
                'telefone_cliente' => $data->telefoneCliente,
                'nif_cliente' => $data->nifCliente,
                'email_cliente' => $data->emailCliente,
                'endereco_cliente' => $data->enderecoCliente,
                'tipoFatura' => 1,
                'observacao' => $data->observacao,
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at']
            ]);
            //Gerar o codigo de barra
            DB::table('notas_creditos')->where('id', $notaCreditoId)->update([
                'codigoBarra' => $this->getCodigoBarra($notaCreditoId, $data->clienteId)
            ]);

            if ($anulado) {
                DB::table('facturas')->where('id', $data->facturaId)->update([
                    'anulado' => 'Y'
                ]);
            } else {
                DB::table('facturas')->where('id', $data->facturaId)->update([
                    'retificado' => 'Y'
                ]);
            }
            foreach ($data->items as $item) {
                $item = (object)$item;
                DB::table('nota_credito_items')->insert([
                    'produtoId' => $item->produtoId,
                    'quantidade' => 1,
                    'nomeProduto' => $item->nomeProduto,
                    'taxa' => $item->taxa,
                    'valorIva' => $item->valorIva,
                    'taxaIva' => $item->taxaIva,
                    'nDias' => $item->nDias,
                    'sujeitoDespachoId' => $item->sujeitoDespachoId,
                    'tipoMercadoriaId' => $item->tipoMercadoriaId,
                    'especificacaoMercadoriaId' => $item->especificacaoMercadoriaId,
                    'desconto' => $item->desconto,
                    'valorDesconto' => $item->valorDesconto,
                    'valorImposto' => $item->valorImposto,
                    'total' => $item->total,
                    'totalIva' => $item->totalIva,
                    'notaCreditoId' => $notaCreditoId,
                ]);
            }
            DB::commit();
            return $notaCreditoId;

        }catch (\Error $e){
            DB::rollBack();
        }
    }

    public function createNotaCreditoAeroportuario($data, $anulado = false)
    {
        try {
            DB::beginTransaction();
            $notaCreditoId = DB::table('notas_creditos')->insertGetId([
                'texto_hash' => $data['texto_hash'],
                'facturaId' => $data->facturaId,
                'tipo_documento' => $data->tipoDocumento,
                'tipoDocumento' => $data->tipoDocumento,
                'formaPagamentoId' => $data->formaPagamentoId,
                'observacao' => $data->observacao,
                'isencaoIVA' => $data->isencaoIVA ? 'Y' : 'N',
                'taxaRetencao' => $data->taxaRetencao,
                'valorRetencao' => $data->valorRetencao,
                'numSequencia' => $data->numSequencia,
                'numDoc' => str_replace("PP", "FP", $data->numDoc),
                'hashValor' => $data->hashValor,
                'empresa_id' => auth()->user()->empresa_id,
                'centroCustoId' => session()->get('centroCustoId'),
                'user_id' => auth()->user()->id,
                'operador' => auth()->user()->name,
                'clienteId' => $data->clienteId,
                'nome_do_cliente' => $data->nomeCliente,
                'nomeProprietario' => $data->nomeProprietario,
                'telefone_cliente' => $data->telefoneCliente,
                'nif_cliente' => $data->nifCliente,
                'email_cliente' => $data->emailCliente,
                'endereco_cliente' => $data->enderecoCliente,
                'tipoDeAeronave' => $data->tipoDeAeronave,
                'pesoMaximoDescolagem' => $data->pesoMaximoDescolagem,
                'dataDeAterragem' => $data->dataDeAterragem,
                'dataDeDescolagem' => $data->dataDeDescolagem,
                'horaDeAterragem' => $data->horaDeAterragem,
                'horaDeDescolagem' => $data->horaDeDescolagem,
                'peso' => $data->pesoTotal,
                'horaExtra' => $data->horaExtra,
                'tipoDocumento' => $data->tipoDocumento,
                'taxaIva' => $data->taxaIva,
                'cambioDia' => $data->cambioDia,
                'contraValor' => $data->contraValor,
                'valorliquido' => $data->valorliquido,
                'totalDesconto' => $data->valorDesconto,
                'valorIliquido' => $data->valorIliquido,
                'valorImposto' => $data->valorImposto,
                'tipoFatura' => 2,
                'total' => $data->total,
                'moeda' => $data->moeda,
                'moedaPagamento' => $data->moedaPagamento,
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at']
            ]);
            //Gerar o codigo de barra
            DB::table('notas_creditos')->where('id', $notaCreditoId)->update([
                'codigoBarra' => $this->getCodigoBarra($notaCreditoId, $data->clienteId)
            ]);
            if ($anulado) {
                DB::table('facturas')->where('id', $data->facturaId)->update([
                    'anulado' => 'Y'
                ]);
            } else {
                DB::table('facturas')->where('id', $data->facturaId)->update([
                    'retificado' => 'Y'
                ]);
            }
            foreach ($data->items as $item) {
                $item = (object)$item;
                $peso = null;
                if ($item->produtoId == 7 || $item->produtoId == 12 || $item->produtoId == 13) {
                    $peso = $item->peso;
                }
                DB::table('nota_credito_items')->insert([
                    'produtoId' => $item->produtoId,
                    'nomeProduto' => $item->nomeProduto,
                    'horaEstacionamento' => $item->horaEstacionamento,
                    'taxa' => $item->taxa,
                    'taxaLuminosa' => $item->taxaLuminosa,
                    'taxaAduaneiro' => $item->taxaAduaneiro,
                    'sujeitoDespachoId' => $item->sujeitoDespachoId,
                    'peso' => $peso ?? null,
                    'horaExtra' => $item->horaExtra,
                    'taxaAbertoAeroporto' => $item->taxaAbertoAeroporto,
                    'valorImposto' => $item->valorImposto,
                    'total' => $item->total,
                    'totalIva' => $item->totalIva,
                    'horaAberturaAeroporto' => $item->horaAberturaAeroporto,
                    'horaFechoAeroporto' => $item->horaFechoAeroporto,
                    'desconto' => $item->desconto,
                    'valorDesconto' => $item->valorDesconto,
                    'taxaIva' => $item->taxaIva,
                    'valorIva' => $item->valorIva,
                    'notaCreditoId' => $notaCreditoId
                ]);
            }
            DB::commit();
            return $notaCreditoId;
        }catch (\Error $error){
            DB::rollBack();
        }
    }

    public function getCodigoBarra($notaCreditoId, $clienteId)
    {
        return "1000" . $clienteId . "" . $notaCreditoId . "" . auth()->user()->id;
    }

}
