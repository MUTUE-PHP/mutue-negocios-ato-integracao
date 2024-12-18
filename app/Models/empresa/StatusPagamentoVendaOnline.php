<?php

namespace App\Models\empresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusPagamentoVendaOnline extends Model
{
    protected $connection = 'mysql2';
    protected $table ='statuspagamentovendaonline';

    protected $fillable = [
        'id',
        'designacao',
    ];

}
