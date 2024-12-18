<?php

namespace App\Infra\Repository\Admin;

use App\Models\admin\Empresa as EmpresaDatabase;
use App\Models\admin\LogsUpdatePassword;
use App\Models\empresa\User as UserDatabase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ClienteRepository
{

    public function getCliente($id)
    {
        $cliente = EmpresaDatabase::with(['tipocliente', 'tiporegime'])->where('id', $id)->first();
        return $cliente;
    }
    public function getClientes()
    {
        $cliente = EmpresaDatabase::with(['tipocliente', 'tiporegime'])->where('id', '!=', 1)->get();
        return $cliente;
    }
    public function resetarSenhaDoCliente($cliente, $novaSenha)
    {
        $user = UserDatabase::where('email', $cliente->email)->update([
            'password' => Hash::make($novaSenha)
        ]);
        $logUpdatePassword = LogsUpdatePassword::where('empresa_id', $cliente->id)->first();

        if ($logUpdatePassword) {
            $logUpdatePassword->update([
                'created_at' => Carbon::now(),
                'users_id' => auth()->user()->id,
                'password' => $novaSenha
            ]);
        } else {
            LogsUpdatePassword::create([
                'empresa_id' => $cliente->id,
                'users_id' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'password' => $novaSenha
            ]);
        }
        return $user;
    }
}
