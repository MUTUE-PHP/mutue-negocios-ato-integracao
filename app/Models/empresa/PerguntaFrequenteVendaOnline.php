<?php

namespace App\Models\empresa;
use Illuminate\Database\Eloquent\Model;

class PerguntaFrequenteVendaOnline extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'perguntas_frequentes';
    protected $fillable = [
        'pergunta',
        'resposta',
    ];
}
