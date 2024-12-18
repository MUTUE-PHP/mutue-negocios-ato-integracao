export default class FaturaItem {

    produtoId;
    nomeProduto;
    precoCompraProduto;
    precoVendaProduto;
    quantidadeProduto;
    descontoProoduto;
    taxa;
    armazemId;
    isEstocavel;
    quantidadeCritica;
    quantidadeMinima;
    quantidadeStock;

    constructor(produtoId, nomeProduto, precoCompraProduto, precoVendaProduto, armazemId, isEstocavel, quantidadeCritica, quantidadeMinima, quantidadeStock, quantidadeProduto, descontoProoduto, taxa) {
        this.produtoId = produtoId;
        this.nomeProduto = nomeProduto;
        this.precoCompraProduto = precoCompraProduto ?? 0;
        this.precoVendaProduto = precoVendaProduto;
        this.quantidadeProduto = quantidadeProduto;
        this.descontoProoduto = descontoProoduto;
        this.armazemId = armazemId;
        this.isEstocavel = isEstocavel;
        this.quantidadeCritica = quantidadeCritica;
        this.quantidadeMinima = quantidadeMinima;
        this.quantidadeStock = quantidadeStock;
        this.taxa = taxa ?? 0;
    }

    get getArmazemId(){
        return this.armazemId
    }
    get isEstocavel(){
        return this.isEstocavel;
    }
    get getQuantidadeCritica(){
        return this.quantidadeCritica;
    }
    get getQuantidadeMinima(){
        return this.quantidadeMinima;
    }
    get getQuantidadeStock(){
        return this.quantidadeStock;
    }

    get getProdutoId() {
        return this.produtoId;
    }

    get getNomeProduto() {
        return this.nomeProduto;
    }

    get getPrecoCompraProduto() {
        return this.precoCompraProduto;
    }
    get getPrecoVendaProduto() {
        return this.precoVendaProduto;
    }
    get getQuantidadeProduto() {
        return this.quantidadeProduto;
    }
    get getDescontoProoduto() {
        return this.descontoProoduto;
    }

    get getTaxa() {
        return this.taxa;
    }
    get subTotalTaxaIva() {
        return (this.precoVendaProduto * this.quantidadeProduto * this.taxa ?? 0) / 100
    }
    get subTotalPrecoProduto() {
        return this.precoVendaProduto * this.quantidadeProduto ?? 1;
    }
    get subTotalDesconto() {
        return (this.precoVendaProduto * this.quantidadeProduto * this.descontoProoduto ?? 0) / 100
    }
    get subTotalIncidencia() {
        return (this.precoVendaProduto * this.quantidadeProduto) - this.subTotalDesconto;
    }
    get subTotalRetencao() {
        return 0;
    }
}
