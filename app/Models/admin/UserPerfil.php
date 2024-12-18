<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class UserPerfil extends Model
{

    public $timestamps = false;
    protected $connection = 'mysql';

    protected $table = "user_perfil";

    protected $fillable = [
        'user_id',
        'perfil_id'
    ];
}
