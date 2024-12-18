<?php

namespace App\Infra\Repository\Admin;

use App\Models\admin\Role;
use Illuminate\Support\Str;

class RoleRepository
{
    public function listarPerfils(){
        return Role::paginate();
    }
    public function getPerfilPeloUuid($uuid){
        return Role::where('uuid', $uuid)->first();
    }
    public function store($data)
    {
        $perfil = Role::create([
            'designacao' => $data['designacao'],
            'status_id' => $data['status_id'],
            'user_id' => auth()->user()->id,
            'uuid' => Str::uuid()
        ]);
        return $perfil;
    }
    public function update($data)
    {
        $perfil = Role::where('id', $data['id'])->update([
                'designacao' => $data['designacao'],
                'status_id' => $data['status_id'],
                'user_id' => auth()->user()->id
            ]);
        return $perfil;
    }
}
