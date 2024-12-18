<?php

namespace App\Models\empresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BonusCartaoCliente extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'bonus_cartao_cliente';
    use SoftDeletes;

    protected $fillable = [
        'id',
        'bonus',
        'user_id',
        'empresa_id',
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
