<?php

namespace App\Models\empresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonusCartaoClienteRange extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'bonus_cartao_cliente_range';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'valorInicial',
        'valorFinal',
        'empresa_id',
        'user_id',
        'created_at',
        'updated_at'
    ];
    public function empresa()
    {
        return $this->belongsTo(Empresa_Cliente::class, 'empresa_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
