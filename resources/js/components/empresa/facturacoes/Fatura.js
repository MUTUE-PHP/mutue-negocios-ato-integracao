const numeroPorExtenso = require('numero-por-extenso');


export default class Fatura {

    clienteId;
    nomeCliente;
    nifCliente;
    telefoneCliente;
    emailCliente;
    enderecoCliente;
    contaCorrente;
    formaPagamentoId;
    armazemId;
    desconto;
    isRetencao;
    tipoDocumento;
    totalEntregue;
    valorMulticaixa;
    valorCash;
    items = []


    constructor(clienteId, nomeCliente, nifCliente, telefoneCliente, emailCliente, enderecoCliente, contaCorrente, formaPagamentoId, armazemId, desconto, isRetencao, tipoDocumento, totalEntregue, valorMulticaixa, valorCash) {

        if (Fatura.instance) {
            return Fatura.instance;
        }
        this.clienteId = clienteId;
        this.nomeCliente = nomeCliente;
        this.nifCliente = nifCliente;
        this.telefoneCliente = telefoneCliente;
        this.emailCliente = emailCliente;
        this.enderecoCliente = enderecoCliente;
        this.contaCorrente = contaCorrente;
        this.formaPagamentoId = formaPagamentoId;
        this.armazemId = armazemId;
        this.desconto = desconto;
        this.isRetencao = isRetencao;
        this.tipoDocumento = tipoDocumento;
        this.totalEntregue = totalEntregue;
        this.valorMulticaixa = valorMulticaixa;
        this.valorCash = valorCash;
        Fatura.instance = this;
    }

    addItem(faturaItem) {
        const index = this.items.findIndex((item) => item.produtoId === faturaItem.produtoId);
        if (index !== -1) {
            this.items[index].quantidadeProduto += 1;
        } else {
            this.items.push(faturaItem);
        }
    }

    get getItems() {
        return this.items;
    }

    get getClienteId() {
        return this.clienteId;
    }

    get getNomeCliente() {
        return this.nomeCliente;
    }

    get getNifCliente() {
        return this.nifCliente;
    }

    get getTelefoneCliente() {
        return this.telefoneCliente;
    }

    get getEmailCliente() {
        return this.emailCliente;
    }

    get getEnderecoCliente() {
        return this.enderecoCliente;
    }

    get getContaCorrente() {
        return this.contaCorrente;
    }

    get getFormaPagamentoId() {
        return this.formaPagamentoId;
    }

    get getArmazemId() {
        return this.armazemId;
    }

    get getDesconto() {
        return this.desconto;
    }

    get tipoDocumento(){
        return this.tipoDocumento;
    }

    get getValorMulticaixa(){
        return this.valorMulticaixa;
    }
    get getValorCash(){
        return this.valorCash;
    }

    get getIsRetencao() {
        return this.isRetencao;
    }

    get totalPrecoFactura() {
        let total = 0;
        this.items.forEach((item => {
            total += item.subTotalPrecoProduto
        }))
        return total;
    }
    get totalPagar() {
        return this.totalPrecoFactura + this.totalIva + this.totalRetencao - this.totalDesconto
    }
    get totalTroco() {
        const troco = this.totalEntregue - this.totalPagar;
        if (troco < 0 || this.totalEntregue <= 0) {
            return 0
        }
        return troco;
    }
    get totalDesconto() {
        let total = 0;
        this.items.forEach((item => {
            total += item.subTotalDesconto
        }))
        return total + (this.totalPrecoFactura * this.desconto) / 100
    }
    get totalRetencao() {
        if (this.isRetencao) {
            return (this.totalPrecoFactura * 6.5) / 100
        }
        return 0
    }
    get totalIva() {
        let total = 0;
        this.items.forEach((item => {
            total += item.subTotalTaxaIva
        }))
        return total;
    }
    get valorExtenso() {
        return numeroPorExtenso.porExtenso(this.totalPagar);
    }
}
