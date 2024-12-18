<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;
use Keygen\Keygen;

class Moeda extends Model
{
    protected $fillable = [
        'designacao',
        'codigo',
        'cambio',
    ];
}
