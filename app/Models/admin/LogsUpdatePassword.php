<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class LogsUpdatePassword extends Model
{
    protected $connection = 'mysql';
    protected $table="logsupdatepassword";

    protected  $fillable = [
        'empresa_id',
        'users_id',
        'created_at',
        'updated_at',
        'password'
    ];

    public function empresa(){
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'users_id');
    }
}
