<template>
    <div class="facturaRegibo">
        <!-- MODAL CARTÃO CLIENTE  -->
        <div class="modal fade" id="modalCartaoCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="width: 500px">
                    <div class="modal-header text-center" id="logomarca-header" style="background-color: #194969">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="white">&times;</span>
                        </button>
                        <h4 class="smaller">
                            <i class="fa fa-money bigger-110 text-default"></i> PAGAR COM CARTÃO DO CLIENTE
                        </h4>
                    </div>
                    <div class="modal-body" style>
                        <form method="POST" class="form-horizontal validation-form" role="form">
                            <input type="hidden" name="_token" />

                            <div class="tabbable">
                                <div class="tab-content profile-edit-tab-content" style="padding: 8px 19px 47px">
                                    <div id="edit-basic" class="tab-pane in active">
                                        <div class="box box-primary">
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="dataFinal" class="control-label">NÚMERO DO CARTÃO
                                                            CLIENTE<b class="red fa fa-question-circle"></b></label>
                                                        <input type="text" style="font-size: 35px;height: 50px;" autofocus
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix form-actions">
                                <div class="col-md-12" style="display: flex;justify-content: center;">
                                    <button style="    padding-top: 15px;
    padding-bottom: 15px;
    padding-left: 50px;
    padding-right: 50px;
    font-size: 18px;" class="btn btn-info" type="submit">
                                        APLICAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIM MODAL CARTÃO CLIENTE  -->
        <div>
            <div class="row">
                <div>
                    <a @click.prevent="imprimirRelatorioDiario" title="Relatório de venda do dia" target="_blanck"
                        class="btn btn-primary widget-box widget-color-blue" id="botoes">
                        <i class="fa fa-print text-default"></i> Relatório do dia
                    </a>
                    <!-- <a href="#vendaRapida" data-toggle="modal" title="vendas rapidas" target="_blanck"
                        class="btn btn-primary widget-box widget-color-blue" id="botoes">
                        Descrição adicional
                    </a> -->
                    <div class="search-form-text" id="headerTitle">
                        <a href="/empresa/home" style="color:white">

                            <div class="search-text">
                                <i class="menu-icon glyphicon glyphicon-step-backward"></i>
                                VOLTAR PÁGINA INICIAL

                            </div>
                        </a>


                        <div class="search-text" id="valorPgt">
                            Total {{ fatura.totalPagar | currency }}
                            <i class="menu-icon fa fa-opencart">
                                <span id="quantProdutoCarrinho">
                                    {{ fatura.getItems.length }}
                                </span>
                            </i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="content-facturacao">
                <div class="col-md-4 grid-facturacao" id="produto-item">
                    <div class="clearfix">
                        <div class="pull-right tableTools-container"></div>
                    </div>
                    <div id="info-total">
                        <div>
                            <div class="total-item">
                                TOTAL A PAGAR<span>{{ fatura.totalPagar | currency }}</span>
                            </div>
                            <div class="total-item">TROCO<span>{{ fatura.totalTroco | currency }}</span></div>
                            <div class="total-item">DESCONTO <span>{{ fatura.totalDesconto | currency }}</span></div>
                            <div class="total-item"> TOTAL DA FATURA <span>{{ fatura.totalPrecoFactura | currency }}</span>
                            </div>
                            <div class="total-item"> TOTAL IVA <span>{{ fatura.totalIva | currency }}</span></div>
                            <div class="total-item">
                                TOTAL RETENÇÃO
                                <span>{{ fatura.totalRetencao | currency }}</span>
                            </div>
                            <div class="total-item">
                                VALOR EXTENSO
                                <span style="color: red">{{ fatura.valorExtenso }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="table-header">PRODUTOS E SERVIÇOS</div>

                    <table class="table table-striped table-hover" v-if="fatura.getItems.length > 0">
                        <thead style="display: table; width: 100%; table-layout: fixed">
                            <tr>
                                <th style="width: 70%">DESCRIÇÃO</th>
                                <th style="text-align:right; width: 30%">PREÇO UNIT.</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody style="display: block; height: 430px; overflow: auto">
                            <tr v-for="prod in fatura.getItems" :key="prod.id"
                                style="display: table; width: 100%; table-layout: fixed">
                                <td style="width: 70%">
                                    {{ prod.nomeProduto.toUpperCase() }}
                                    <b>({{ prod.quantidadeProduto | formatQt }})</b> <br />
                                </td>
                                <td style="text-align: right; width: 25%">
                                    <span>{{ prod.precoVendaProduto | currency }}</span>
                                </td>

                                <td style="width: 5%;">
                                    <div class="hidden-sm hidden-xs action-buttons">
                                        <a class="red">
                                            <i class="ace-icon glyphicon glyphicon-remove bigger-130"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <td colspan="1">
                                <button type="button" class="btn btn-white btn-danger btn-sm mt-4" @click="limparTodosItem">
                                    <i class="ace-icon glyphicon glyphicon-remove" aria-hidden="true"></i>
                                    Limpar
                                </button>
                            </td>
                        </tfoot>
                    </table>
                    <div v-else id="semProduto">
                        <div class="semProdutoDescription">
                            <div>
                                <i class="glyphicon glyphicon-list"></i>
                            </div>
                            <div>
                                <div class="text">NÃO EXISTE PRODUTOS/SERVIÇOS NA LISTA</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label bold" for="preco_compra">
                                LOJA/ARMAZÉM
                            </label>
                            <Select2 :options="optionsArmazens" @select="updateArmazem" v-model="fatura.armazemId">
                            </Select2>
                        </div>
                        <div class="col-md-12" style="z-index: 1">
                            <div class="row" style="margin-top: 10px">
                                <!-- PESQUISA PRODUTOS  -->
                                <div class="col-md-12">
                                    <span class="input-form-icon">
                                        <form action id="search-form">
                                            <input type="text" v-model="searchProduto" @input="searchProdutos"
                                                class="col-md-12 search-query" placeholder="Buscar produtos e serviços..."
                                                autocomplete="off" />
                                            <i class="ace-icon fa fa-search nav-search-icon"></i>
                                        </form>
                                    </span>
                                </div>
                                <!-- FIM PESQUISA PRODUTOS  -->
                            </div>
                            <!-- <hr> -->
                        </div>
                        <div class="col-md-12" v-if="!produtos.length" style="margin-top: 10px">
                            <div id="semProduto">
                                <div class="semProdutoDescription">
                                    <div>
                                        <i class="glyphicon glyphicon-list"></i>
                                    </div>
                                    <div>
                                        <div class="text" style="color: red">
                                            NÃO EXISTE PRODUTOS / SERVIÇOS NESTE ARMAZÉM
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="overflow-y: scroll; height: 620px">
                        <div class="col-md-4 content-produto" v-for="(prod, i) in produtos" :key="i">
                            <div class="widget-box widget-color-dark light-border produto-item produtoItem"
                                style="heigth: 100px" id="widget-box-6">
                                <div class="widget-header">
                                    <div class="widget-title smaller">
                                        <span class="badge badge-danger">{{ prod.precoVendaProduto | currency }}</span>
                                    </div>
                                </div>
                                <div class="widget-body produto-info produtoItem"
                                    @dblclick="adicionarProduto(prod.produtoId)">
                                    <div class="widget-main padding-6">
                                        <div class="alert alert-info">
                                            {{ prod.nomeProduto.toUpperCase() }} <br />
                                            <span v-if="prod.isEstocavel == 'Sim'" style="text-align: right"
                                                class="badge badge-danger">stock: {{
                                                    prod.quantidadeStock | formatQt
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="row">
                        <label class="control-label bold" for="preco_compra">SELECIONAR CLIENTE 
                            <a href="#modalCartaoCliente" data-toggle="modal" style="background: red;padding: 5px;border-radius: 5px;color: white; cursor: pointer;">PAGAR
                                SALDO DO CLIENTE</a>
                        </label>
                        <Select2 :options="optionsClientes" @select="updateCliente" v-model="clienteId">
                        </Select2>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="padding: 0px">
                            <div class="tabbable">
                                <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
                                    <li class="active">
                                        <a data-toggle="tab" href="#dropdown14">PAGAMENTO</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#dropdown16">DESCONTO/RETENÇÃO</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="dropdown14" class="tab-pane in active">
                                        <div class="row">
                                            <div class="col-md-12 inputFormPag">
                                                <label class="control-label bold" for="preco_compra">
                                                    FORMA PAGAMENTO
                                                    <span class="tooltip-target" data-toggle="tooltip" data-placement="top">
                                                        <i class="fa fa-question-circle bold text-danger"></i>
                                                    </span>
                                                </label>
                                                <div class="inputFormPag">
                                                    <Select2 :options="optionsFormasPagamento"
                                                        @select="updateFormasPagamento"
                                                        :disabled="tipoDocumento == 2 || tipoDocumento == 3"
                                                        v-model="formaPagamentoId" id="disabled-pagamento">
                                                    </Select2>
                                                    <a href="#criar_formapagamento" title="Adicionar formas de Pagamento"
                                                        data-toggle="modal" class="pull-right"
                                                        style="margin-top: -27px; position: relative">
                                                        <i class="
                                  fa
                                  icofont-plus-circle
                                  bigger-150
                                  pull-right
                                " style="color: #337ab7"></i>
                                                    </a>
                                                </div>

                                                <div class="inputFormPag" v-if="formaPagamentoId !== 6">
                                                    <label class="control-label bold" for="preco_compra">
                                                        VALOR ENTREGUE
                                                    </label>
                                                    <input type="number"
                                                        :style="requiredValorEntregue ? 'border:1px solid red' : ''"
                                                        :disabled="tipoDocumento == 2 || tipoDocumento == 3"
                                                        v-model="totalEntregue" @input="updateTotalEntregue"
                                                        id="disabled-valor_pagar" class="form-control" />
                                                </div>
                                                <div class="inputFormPag" v-if="formaPagamentoId == 6">
                                                    <label class="control-label bold" for="preco_compra">VALOR
                                                        MULTICAIXA</label>
                                                    <input type="number"
                                                        :style="requiredDuplo ? 'border:1px solid red' : ''"
                                                        v-model="valorMulticaixa" @input="updateValorMulticaixa"
                                                        id="valorMulticaixa" class="form-control" />
                                                </div>
                                                <div class="inputFormPag" v-if="formaPagamentoId == 6">
                                                    <label class="control-label bold" for="preco_compra">VALOR
                                                        CASH</label>
                                                    <input type="number"
                                                        :style="requiredDuplo ? 'border:1px solid red' : ''"
                                                        v-model="valorCash" @input="updateValorCash" id="valorCash"
                                                        class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="dropdown16" class="tab-pane">
                                        <div class="row">
                                            <div class="col-md-12 inputFormPag">
                                                <div class="inputFormPag">
                                                    <label class="control-label bold" for="preco_compra">
                                                        DESCONTO
                                                    </label>

                                                    <input type="number" v-model="desconto" @input="updateDescontoGeral"
                                                        max="100" :min="1" class="form-control" />
                                                </div>
                                                <div class="checkbox">
                                                    <label class="block">
                                                        <input name="form-field-checkbox" v-model="fatura.isRetencao"
                                                            type="checkbox" class="ace input-sm" />
                                                        <span class="lbl bigger-100">APLICAR RETENÇÃO NA FONTE</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" style="padding: 0px">
                                            <div class="tabbable">
                                                <ul class="nav nav-tabs padding-12 tab-color-blue background-blue " id="myTab4">
                                                    <li class="active">
                                                        <a data-toggle="tab" href="#dropdown17">TIPO DOCUMENTO</a>
                                                    </li>
                                                    <li>
                                                        <a data-toggle="tab" href="#dropdown18">FORMATO DOCUMENTO</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div id="dropdown17" class="tab-pane in active">
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" id="radio1" value="1"
                                                                    v-model="tipoDocumento" @click="updateTipoDocumento(1)"
                                                                    class="ace input-sm" />
                                                                <span class="lbl bigger-100">FT.RECIBO</span>
                                                            </label>
                                                            <label>
                                                                <input type="radio" id="radio2" value="2"
                                                                    v-model="tipoDocumento" @click="updateTipoDocumento(2)"
                                                                    class="ace input-sm" />
                                                                <span class="lbl bigger-100">FATURA</span>
                                                            </label>

                                                            <label>
                                                                <input type="radio" id="radio3" value="3"
                                                                    v-model="tipoDocumento" @click="updateTipoDocumento(3)"
                                                                    class="ace input-sm" />
                                                                <span class="lbl bigger-100">PROFORMA</span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div id="dropdown18" class="tab-pane">
                                                        <div class="row">
                                                            <div class="col-md-12 FormatoImpressao">
                                                                <div class="form-group">
                                                                    <div class="form-check" style="float: left">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="radio1" />
                                                                            A4
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12" style="padding: 0px">
                                            <div class="tabbable">
                                                <ul class=" nav nav-tabs padding-12 tab-color-blue background-blue " id="myTab4">
                                                    <li class="active">
                                                        <a data-toggle="tab" href="#clienteDiverso1">DADOS CLIENTE</a>
                                                    </li>

                                                    <li>
                                                        <a data-toggle="tab" href="#clienteDiverso2">DADOS CLIENTE</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div id="clienteDiverso1" class="tab-pane in active">
                                                        <div class="row inputCliente">
                                                            <div class="col-md-12 inputFormPag">
                                                                <label class="control-label bold" for="preco_compra">
                                                                    NOME
                                                                    <span class="tooltip-target" data-toggle="tooltip"
                                                                        data-placement="top">
                                                                        <i class="fa fa-question-circle bold text-danger"></i>
                                                                    </span>
                                                                </label>
                                                                <input autocomplete="true" v-model="fatura.nomeCliente"
                                                                    type="text" class="form-control" />
                                                            </div>
                                                            <div class="col-md-12 inputFormPag">
                                                                <label class="control-label bold" for="preco_compra">
                                                                    NIF
                                                                    <span class="tooltip-target" data-toggle="tooltip"
                                                                        data-placement="top">
                                                                        <i class="
                                          fa fa-question-circle
                                          bold
                                          text-danger
                                        "></i>
                                                                    </span>
                                                                </label>
                                                                <input type="text" v-model="fatura.nifCliente"
                                                                    class="form-control" />
                                                            </div>
                                                            <div class="col-md-12 inputFormPag">
                                                                <label class="control-label bold" for="preco_compra">
                                                                    TELEFONE
                                                                    <span class="tooltip-target" data-toggle="tooltip"
                                                                        data-placement="top">
                                                                        <i class="
                                          fa fa-question-circle
                                          bold
                                          text-danger
                                        "></i>
                                                                    </span>
                                                                </label>
                                                                <input type="text" v-model="fatura.telefoneCliente"
                                                                    class="form-control" />
                                                            </div>

                                                            <div class="col-md-12 inputFormPag">
                                                                <label class="control-label bold" for="preco_compra">
                                                                    ENDEREÇO
                                                                    <span class="tooltip-target" data-toggle="tooltip"
                                                                        data-placement="top">
                                                                        <i class="
                                          fa fa-question-circle
                                          bold
                                          text-danger
                                        "></i>
                                                                    </span>
                                                                </label>

                                                                <input type="text" v-model="fatura.enderecoCliente"
                                                                    class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="clienteDiverso2" class="tab-pane">
                                                        <div class="row inputCliente">
                                                            <div class="col-md-12 inputFormPag">
                                                                <label class="control-label bold" for="conta_corrente">
                                                                    CONTA CORRENTE
                                                                </label>
                                                                <input autocomplete="true" disabled
                                                                    v-model="fatura.contaCorrente" type="text"
                                                                    class="form-control" />
                                                            </div>
                                                            <div class="col-md-12 inputFormPag">
                                                                <label class="control-label bold" for="preco_compra">
                                                                    EMAIL
                                                                </label>

                                                                <input type="text" v-model="fatura.emailCliente"
                                                                    class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12" id="btnFechoCaixa">
                                                            <a data-toggle="modal" href="#modalFechoCaixa">
                                                                <i class="menu-icon glyphicon glyphicon-time"></i>
                                                                FECHO DE CAIXA
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="row">
            <div class="" style="float: right; padding-right: 10px; margin-right: 10px">
                <a href="#" id="btnFacturacao" style="width: 100px;" class="btn btn-app btn-primary btn-xs"
                    @click.prevent="faturar">
                    <i class="ace-icon fa fa-print bigger-160"></i>
                    FATURAR
                </a>
            </div>
        </div>
    </div>
</template>
<script>
import Select2 from "v-select2-component";
import PesquisarCliente from "./PesquisarCliente.vue";
import DatePicker from "vue2-datepicker";
import VueNumericInput from "vue-numeric-input";
import axios from "axios";
import Fatura from "./Fatura";
import FaturaItem from "./FaturaItem";


export default {
    props: ['armazens', 'clientes', 'formaspagamentos'],
    components: {
        Select2,
        PesquisarCliente,
        DatePicker,
        VueNumericInput,
    },
    data() {
        return {
            requiredDuplo: false,
            requiredValorEntregue: false,
            produtos: [],
            searchProduto: null,
            fatura: {},
            cliente: {},
            formaPagamentoId: null,
            tipoDocumento: 1,//fatura recibo
            armazemId: null,
            clienteId: null,
            desconto: 0,
            valorMulticaixa: 0,
            valorCash: 0,
            isRetencao: false,
            totalEntregue: 0,
            optionsArmazens: [],
            optionsClientes: [],
            optionsFormasPagamento: []
        }
    },
    methods: {
        limparTodosItem() {

        },
        selecionarArmazem() {
            alert('teste')

        },
        createFormasPagamentoSelect2() {
            this.formaspagamentos.forEach((item, index) => {
                this.optionsFormasPagamento.push({
                    id: item.id,
                    text: item.descricao.toUpperCase(),
                });
            });
            this.formaPagamentoId = this.formaspagamentos[0]['id']

        },
        createClientesSelect2() {
            this.clientes.forEach((item, index) => {
                this.optionsClientes.push({
                    id: item.id,
                    text: item.nome.toUpperCase(),
                });
            });
            this.clienteId = this.clientes[0]['id']

        },
        createArmazensSelect2() {
            this.armazens.forEach((item, index) => {
                this.optionsArmazens.push({
                    id: item.id,
                    text: item.designacao.toUpperCase(),
                });
            });
            this.armazemId = this.armazens[0]['id']
        },
        adicionarProduto(produtoId) {

            if (!navigator.onLine) {
                location.reload();
                return;
            }

            this.cliente = this.buscarCliente(this.fatura.clienteId);
            const produto = this.buscarProdutos(produtoId);

            if (!produto) {
                alert('Produto não existente');
                return;
            }
            // this.fatura = new Fatura(
            //     this.cliente.id,
            //     this.cliente.nome,
            //     this.cliente.nif,
            //     this.cliente.telefone_cliente,
            //     this.cliente.email,
            //     this.cliente.endereco,
            //     this.cliente.conta_corrente,
            //     this.formaPagamentoId,
            //     this.armazemId,
            //     this.desconto,
            //     this.isRetencao,
            //     this.totalEntregue
            // );
            const quantidade = 1;
            const desconto = 0;
            const faturaItem = new FaturaItem(
                produtoId,
                produto.nomeProduto,
                produto.precoCompraProduto,
                produto.precoVendaProduto,
                produto.armazemId,
                produto.isEstocavel,
                produto.quantidadeCritica,
                produto.quantidadeMinima,
                produto.quantidadeStock,
                quantidade,
                desconto,
                produto.taxaIva
            );

            const produtoByArmazem = this.buscarProdutoPeloIdprodtoIdArmazem(produtoId, this.armazemId);
            const quantidadeAtual = this.buscarQuantidadeCarrinho(produtoId);
            if (produto.isEstocavel == 'Sim' && (quantidadeAtual >= produtoByArmazem.quantidadeStock)) {
                alert('Não existe quantidade suficiente no estoque');
                return;
            }
            this.fatura.addItem(faturaItem);
            console.log(this.fatura);
        },
        buscarQuantidadeCarrinho(produtoId) {
            const item = this.fatura.getItems.find((item) => item.produtoId == produtoId);
            if (!item) return 0;
            return item.quantidadeProduto;

        },
        buscarProdutoPeloIdprodtoIdArmazem(produtoId, armazemId) {
            return this.produtos.find((item) => {
                return item.produtoId == produtoId && item.armazemId == armazemId;
            });
        },

        async listarProdutos(search, armazemId) {
            let response = await axios.get(`/empresa/listarProdutosPorArmazem?search=${search}&armazemId=${armazemId}`);
            if (response.status == 200) {
                this.produtos = response.data;
            }
        },
        buscarCliente(clienteId) {
            const foundItem = this.clientes.find(item => item.id == clienteId);
            if (!foundItem) return null
            return foundItem;
        },
        searchProdutos() {
            this.listarProdutos(this.searchProduto, this.armazemId);
        },
        buscarProdutos(produtoId) {
            return this.produtos.find((item) => {
                return item.produtoId == produtoId;
            });
        },
        updateValorMulticaixa() {
            this.fatura.valorMulticaixa = Number(this.valorMulticaixa);
        },
        updateValorCash() {
            this.fatura.valorCash = Number(this.valorCash);
        },
        updateCliente(clienteData) {
            const cliente = this.buscarCliente(clienteData.id);
            this.fatura.clienteId = cliente.id;
            this.fatura.contaCorrente = cliente.conta_corrente;
            this.fatura.emailCliente = cliente.email;
            this.fatura.enderecoCliente = cliente.endereco;
            this.fatura.nifCliente = cliente.nif;
            this.fatura.nomeCliente = cliente.nome;
            this.fatura.telefoneCliente = cliente.telefone_cliente;
        },
        updateTotalEntregue() {
            if (!this.totalEntregue) {
                this.fatura.totalEntregue = 0;
            } else {
                this.fatura.totalEntregue = Number(this.totalEntregue);
            }
        },
        updateArmazem(armazem) {
            this.armazemId = armazem.id;
            this.fatura.armazemId = this.armazemId;
            this.listarProdutos(this.searchProduto, armazem.id)
        },
        updateFormasPagamento(formaPagamento) {
            this.formaPagamentoId = formaPagamento.id;
            this.fatura.formaPagamentoId = formaPagamento.id;

            this.requiredDuplo = false;
            this.requiredValorEntregue = false;

            this.valorMulticaixa = 0;
            this.valorMulticaixa = 0;

            if (formaPagamento.id == 2) {
                this.totalEntregue = 0;
                this.fatura.totalEntregue = 0;
                this.tipoDocumento = 2
                this.fatura.tipoDocumento = 2;
            }

            else if (formaPagamento.id == 6) {
                this.totalEntregue = 0;
                this.formaPagamentoId = 6;
                this.fatura.formaPagamentoId = 6;
                this.fatura.totalEntregue = 0;
            }

        },
        updateTipoDocumento(valor) {

            this.tipoDocumento = valor;
            this.requiredValorEntregue = false;
            this.fatura.tipoDocumento = valor;
            if (this.tipoDocumento == 2) { // tipo fatura
                this.fatura.formaPagamentoId = 2//pagamento credito
                this.formaPagamentoId = 2;
                this.totalEntregue = 0;
                this.fatura.totalEntregue = 0;
            } else if (this.tipoDocumento == 3) { // tipo proforma
                this.fatura.formaPagamentoId = null;
                this.formaPagamentoId = null;
                this.totalEntregue = 0;
                this.fatura.totalEntregue = 0;
            } else if (this.tipoDocumento == 1) {// tipo fatura
                this.fatura.formaPagamentoId = 1 // pagamento numerario
                this.formaPagamentoId = 1;

            }

        },
        updateDescontoGeral() {
            if (this.desconto > 100) {
                this.desconto = 100;
                this.fatura.desconto = 100;
            } else if (!this.desconto) {
                this.fatura.desconto = 0;
            } else {
                this.fatura.desconto = Number(this.desconto);
            }
        },
        async faturar() {
            if (this.fatura.getItems.length <= 0) {
                this.$toasted.global.defaultError({
                    msg: "Adicionar produtos no carrinho",
                });
                return;
            }

            if (this.formaPagamentoId == 6) {
                if (this.fatura.valorMulticaixa <= 0 || this.fatura.valorCash <= 0) {
                    this.requiredDuplo = true;
                    this.$toasted.global.defaultError({
                        msg: "Informe os valores do pagamentos duplo",
                    });
                    return;

                }
                if ((this.fatura.valorMulticaixa + this.fatura.valorCash) < this.fatura.totalPagar) {
                    this.requiredDuplo = true;
                    this.$toasted.global.defaultError({
                        msg: "O total a pagar é menor",
                    });
                    return;
                } else {
                    this.requiredDuplo = false;
                }
            }

            if (this.formaPagamentoId !== 2 && this.formaPagamentoId !== 6) {
                if (this.fatura.totalEntregue < this.fatura.totalPagar) {
                    this.requiredValorEntregue = true;
                    this.$toasted.global.defaultError({
                        msg: "O valor entregue é menor ao total a pagar",
                    });
                    return;
                } else {
                    this.requiredValorEntregue = false;

                }
            }
            let response = await axios.post(`/empresa/emitirDocumento`, this.fatura);


            console.log(this.fatura);

        },
        criaInstanciaFatura() {
            this.cliente = this.buscarCliente(this.clienteId);
            return new Fatura(
                this.cliente.id,
                this.cliente.nome,
                this.cliente.nif ?? '999999999',
                this.cliente.telefone_cliente ?? '999999999',
                this.cliente.email,
                this.cliente.endereco,
                this.cliente.conta_corrente,
                this.formaPagamentoId,
                this.armazemId,
                this.desconto,
                this.isRetencao,
                this.tipoDocumento,
                this.totalEntregue,
                this.valorMulticaixa,
                this.valorCash
            );
        }
    },

    created() {
        this.createArmazensSelect2();
        this.createClientesSelect2();
        this.createFormasPagamentoSelect2()
        //listar produtos iniciais
        const armazemId = this.armazens[0]['id'];
        const searchProduto = null;
        this.listarProdutos(searchProduto, armazemId);
        this.fatura = this.criaInstanciaFatura();

    }
}
</script>


<style scoped>
.popUp {
    top: -28px;
    left: 0px;
    /* width: 100%; */
    width: 140px;
    /* height: 50px; */
    background: #307ecc;
    color: white;
    border-radius: 5px;
    position: unset;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
}

.produtoItem:hover {
    background: #bcd4e0;
}

.popUp p {
    font-size: 12px;
    margin: 0;
    padding-left: 5px;
}

.FormatoImpressao label {
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    cursor: pointer;
    /* margin-left: -19px; */
    background: #ccc;
    padding: -3px;
    border-radius: 6px;
    padding-right: 11px;
    padding-left: 11px;
    margin-right: 10px;
    color: #333;
}

.FormatoImpressao input {
    height: 25px;
}

#quantProdutoCarrinho {
    position: absolute;
    font-size: 15px;
    color: white;
    font-weight: 600;
}

#btnFechoCaixa {
    margin-top: 10px;
}

#btnFechoCaixa a {
    padding: 10px;
    background: #2f8fce;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
}

::-webkit-scrollbar {
    width: 5px;
}

/* Track */
::-webkit-scrollbar-track {
    box-shadow: inset 0 0 5px grey;
    border-radius: 10px;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #103d54;
    border-radius: 10px;
}

#content-facturacao {
    margin-top: 20px;
}

#content-facturacao {}

.alert {
    padding: 8px;
    font-size: 11px;
}

.nav-tabs.tab-color-blue>li>a {
    background-color: #307ecc;
}

.nav-tabs.tab-color-blue>li.active>a,
.nav-tabs.tab-color-blue>li.active>a:focus,
.nav-tabs.tab-color-blue>li.active>a:hover {
    background-color: white;
    color: #333;
}

#info-total {
    display: flex;
    flex-direction: column;
    border: 1px solid #ccbfbf;
    background: #0c1b25;
    color: white;
    padding: 10px;
    border-radius: 5px 5px 0px 0;
}

.total-item {
    padding: 2px;
    border-bottom: 1px solid #504848;
    display: flex;
    justify-content: space-between;
}

.total-item span {
    color: #fff;
}

.checkbox label input[type="checkbox"].ace+.lbl {
    margin-left: -19px;
    background: #ccc;
    padding: 10px;
    border-radius: 5px;
    color: #333;
}

.radio label input[type="radio"].ace+.lbl {
    margin-left: -19px;

    background: #ccc;
    padding: 4px;
    border-radius: 5px;
    color: #333;
}

.tipoFacturar {
    display: flex;
}

.inputFormPag {
    margin-bottom: 7px;
}

input {
    height: 35px;
    border-radius: 5px !important;
}

.alert-info {
    background: #103d54;
    color: white;
    height: 73px;
}

.widget-color-dark>.widget-header {
    background: #333;
}

.content-produto {
    border-bottom: 1px solid #e2dbdb;
    padding-bottom: 10px !important;
    padding-top: 10px !important;
}

.produto-item:hover {
    cursor: pointer;
}

.produto-info {
    height: 85px;
    color: white;
}

.grid-facturacao {
    border: 1px solid #e8e8e8;
    height: 100%;
}

#content-facturacao {
    height: 688px;
}

.search-query {
    border: 1px solid #6fb3e0;
    border-radius: 4px !important;
    padding-left: 24px;
}

.search-query:focus {
    border: 1px solid #6fb3e0;
}

span.input-form-icon {
    position: relative;
}

span.input-form-icon .ace-icon {
    padding: 0 3px;
    z-index: 2;
    position: absolute;
    top: 1px;
    bottom: 1px;
    left: 3px;
    line-height: 30px;
    display: inline-block;
    color: #6fb3e0 !important;
    font-size: 16px;
}

#icon-remove {
    left: 236px;
    cursor: pointer;
}

table tr,
th {
    height: 20px;
    font-size: 13px;
    font-family: unset;
    cursor: pointer;
}

#semProduto {
    display: flex;
    justify-content: center;
    text-align: center;
    padding-top: 15px;
    border: 1px solid #ccc;
    padding-bottom: 20px;
}

#semProduto .text {
    font-size: 13px;
    font-weight: 500;
    /* letter-spacing: 0.2rem; */
}

.semProdutoDescription {
    display: flex;
    flex-direction: column;
}

#btn-modal-edit-facturacao button {
    margin-top: 20px;
}

.modal-header#modalEditFactura {
    background-color: #307ecc;
    color: white;
}

.modal-header#modalEditFactura h3.smaller {
    color: white !important;
}

.search-form-text #valorPgt {
    display: inline-block;
    font-size: 20px;
    color: #fff;
    text-transform: uppercase;
    background: #333;
    padding: 13px 30px;
    font-weight: 700;
}

#headerTitle {
    display: flex;
    justify-content: space-between;
}

.scroller {
    width: 100%;
    height: 457px;
    overflow-y: scroll;
    scrollbar-color: rebeccapurple green;
}

abbr {
    position: relative;
}

abbr:hover::after {
    background: #add8e6;
    border-radius: 4px;
    bottom: 100%;
    content: attr(title);
    display: block;
    left: 100%;
    padding: 1em;
    position: absolute;
    width: 140px;
    z-index: 99999999;
}
</style>
