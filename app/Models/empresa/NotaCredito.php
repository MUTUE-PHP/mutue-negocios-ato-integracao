<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class NotaCredito extends Model
{
    protected $table = 'notas_creditos';
    protected $connection = 'mysql2';

    protected $fillable = [
        'id',
        'texto_hash',
        'codigo_moeda',
        'clienteId',
        'facturaId',
        'nome_do_cliente',
        'nomeProprietario',
        'telefone_cliente',
        'nif_cliente',
        'email_cliente',
        'endereco_cliente',
        'tipo_documento',
        'numSequencia',
        'numDoc',
        'hashValor',
        'cliente_id',
        'empresa_id',
        'centroCustoId',
        'user_id',
        'operador',
        'paisOrigemId',
        'cartaDePorte',
        'tipoDeAeronave',
        'pesoMaximoDescolagem',
        'dataDeAterragem',
        'dataDeDescolagem',
        'horaDeAterragem',
        'horaDeDescolagem',
        'horaEstacionamento',
        'peso',
        'dataEntrada',
        'dataEntrada',
        'dataSaida',
        'nDias',
        'taxaIva',
        'cambioDia',
        'moeda',
        'moedaPagamento',
        'horaExtra',
        'contraValor',
        'valorConsumo',
        'valorIliquido',
        'valorliquido',
        'valorImposto',
        'taxaImpostoPredial',
        'valorImpostoPredial',
        'totalDesconto',
        'total',
        'codigoBarra',
        'tipoDocumento',
        'formaPagamentoId',
        'tipoOperacao',
        'isencaoIVA',
        'isencaoOcupacao',
        'isencao24hCargaTransito',
        'convertido',
        'anulado',
        'taxaRetencao',
        'valorRetencao',
        'tipoFatura',
        'tipoMercadoria',
        'observacao',

    ];


    public function factura(){
        return $this->belongsTo(Factura::class, 'facturaId');
    }
    public function recibo(){
        return $this->belongsTo(Recibos::class, 'reciboId');

    }


    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

}
