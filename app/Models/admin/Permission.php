<?php

namespace App\Models\admin;

use App\Models\empresa\Role;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $connection = 'mysql';
    protected $table = 'permissions';


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
