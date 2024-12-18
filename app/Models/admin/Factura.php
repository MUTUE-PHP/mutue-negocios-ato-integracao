<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use Keygen\Keygen;

class Factura extends Model
{
    protected $connection = 'mysql';
    protected $table ='facturas';


    const STATUDIVIDA = 1;
    const STATUPAGO = 2;


    protected $fillable = [
        'total_preco_factura',
        'valor_entregue',
        'total_sem_imposto',
        'valor_a_pagar',
        'troco',
        'valor_extenso',
        'codigo_moeda',
        'desconto',
        'precoLicenca',
        'total_iva',
        'multa',
        'nome_do_cliente',
        'telefone_cliente',
        'nif_cliente',
        'statusFactura',
        'email_cliente',
        'endereco_cliente',
        'numeroItems',
        'licenca_id',
        'tipo_documento',
        'observacao',
        'retencao',
        'nextFactura',
        'faturaReference',
        'numSequenciaFactura',
        'numeracaoFactura',
        'tipoFolha',
        'hashValor',
        'formas_pagamento_id',
        'retificado',
        'descricao',
        'empresa_id',
        'canal_id',
        'status_id',
        'user_id',
        'created_at',
        'updated_at',
        'data_vencimento',
    ];


    public static function boot(){

        parent::boot();

        self::creating(function($model){
            $model->faturaReference = mb_strtoupper(Keygen::numeric(9)->generate());
            $model->nextFactura = $model->faturaReference;
        });
    }
    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function ($query) use ($term) {
            $query->where("nome_do_cliente", "like", $term)
            ->orwhere("nif_cliente", "like", $term)
            ->orwhere("numeracaoFactura", "like", $term);
        });
    }

}
