<?php

namespace App\Models\empresa;

use Illuminate\Database\Eloquent\Model;
use Keygen\Keygen;

class UsersCentrosCusto extends Model
{
    protected $connection = 'mysql2';
    protected $table = "users_centro_custo";
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'centro_custo_id', 'status'
    ];




}
