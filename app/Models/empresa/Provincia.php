<?php

namespace App\Models\empresa;

use App\Models\empresa\ExistenciaStock;
use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'cidades';
}
