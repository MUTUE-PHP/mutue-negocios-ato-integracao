<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class UserPermissions extends Model
{

    public $timestamps = false;
    public $connection = 'mysql';

    protected $table = "model_has_permissions";

    protected $fillable = [
        'permission_id',
        'model_id',
        'model_type'

    ];
}
