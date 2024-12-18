<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class SubscricaoVendaOnline extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'subscricao_emails';
    protected $fillable = [
        'email',
        'estado_recebimento',
    ];


}
