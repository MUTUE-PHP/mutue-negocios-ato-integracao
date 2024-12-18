<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;
use Keygen\Keygen;

class TiposEntrega extends Model
{
    protected $connection = 'mysql2';
    protected $table = "tipos_entregas";

   protected $fillable = [
       'designacao',
       'status_id'
   ];

}
