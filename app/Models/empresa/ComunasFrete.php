<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComunasFrete extends Model
{
    protected $table = 'comunas';
    protected $connection = 'mysql2';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'designacao',
        'municipioId',
        'valor_entrega',
        'statusId',
    ];

    public function municipio()
    {
        return $this->belongsTo(MunicipiosFrete::class, 'municipioId');
    }

}
