<?php

namespace App\Infra\Repository\Admin;

use App\Models\admin\Permission;
use App\Models\admin\PermissionRole;
use Illuminate\Support\Facades\DB;

class PermissaoRepository
{
    public function listarPermissoes(){
        return Permission::paginate();
    }
    public function listarPermissoesSemPaginacao(){
        return Permission::get();

    }
    public function getPermissoesPorRole($roleId){
        $permissoes = PermissionRole::where('role_id', $roleId)->get();
        return $permissoes;
    }
    public function checkPermissaoPorRole($permissaoId, $roleId){
        $permissao = PermissionRole::where('role_id', $roleId)
            ->where('permission_id', $permissaoId)
            ->first();
        if ($permissao) {
            return DB::connection('mysql')->table('role_has_permissions')->where('role_id', $roleId)
                ->where('permission_id', $permissaoId)->delete();
        } else {
            return DB::connection('mysql')->table('role_has_permissions')->insert([
                'permission_id' => $permissaoId,
                'role_id' => $roleId
            ]);
        }
    }

}
