<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $connection = 'mysql2';
    protected $table ='inventarios';
    protected $fillable = [
        'centroCustoId'
    ];


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function armazem(){
        return $this->belongsTo(Armazen::class,'armazem_id');
    }
    public function inventarioItems()
    {
        return $this->hasMany(InventarioItems::class, 'inventario_id', 'id');
    }
}
