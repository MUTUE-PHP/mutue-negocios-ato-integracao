<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MunicipiosFrete extends Model
{
    use SoftDeletes;
    protected $table = 'municipios_';
    protected $connection = 'mysql2';

    protected $fillable = [
        'id',
        'designacao',
        'cidade_id',
        'valor_entrega',
        'status_id',
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'cidade_id');
    }

}
