<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;

class Cambio extends Model
{

    protected $fillable = [
        'designacao',
        'valor',        
    ];

    public $timestamps = false;
}