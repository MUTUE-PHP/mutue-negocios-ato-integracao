<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empresas = [
            [
                'nome' => 'MUTUE SOLUÇÕES TECNOLÓGICAS INTELIGENTES LDA',
                'pessoal_Contacto' => '922969192',
                'endereco' => 'RUA NOSSA SENHORA DA MUXIMA, Nº 10-8º ANDAR',
                'empresa_id' => null,
                'pais_id' => 1,
                'saldo' => 0,
                'nif' => '5000977381',
                'gestor_cliente_id' => 1,
                'tipo_cliente_id' => 2,
                'tipo_regime_id' => 3,
                'logotipo' => 'admin/UMA.jpg',
                'website' => 'mutue.net',
                'email' => 'geral@mutue.ao',
                'referencia' => '7B43VY8',
                'pessoa_de_contacto' => null,
                'status_id' => 1,
                'canal_id' => 3,
                'user_id' => 1,
                'cidade' => 'Luanda',
                'file_alvara' => null,
                'file_nif' => null,
                'licenca' => null,
                'venda_online' => 'N',
                'ultimo_acesso' => null
            ],
            [
                'nome' => 'BOUTIQUE DA MISSÃO',
                'pessoal_Contacto' => '934983900',
                'endereco' => 'RUA NOSSA SENHORA DA MUXIMA, Nº 10-8º ANDAR',
                'empresa_id' => null,
                'pais_id' => 1,
                'saldo' => 0,
                'nif' => '5000977160',
                'gestor_cliente_id' => 1,
                'tipo_cliente_id' => 2,
                'tipo_regime_id' => 3,
                'logotipo' => 'admin/UMA.jpg',
                'website' => 'boutique.ao',
                'email' => 'kuzuata@kimakietu.ao',
                'referencia' => '6DD2HZM',
                'pessoa_de_contacto' => null,
                'status_id' => 1,
                'canal_id' => 3,
                'user_id' => 1,
                'cidade' => 'Luanda',
                'file_alvara' => null,
                'file_nif' => null,
                'licenca' => 'ativo',
                'venda_online' => 'Y',
                'ultimo_acesso' => null
            ]
        ];

        foreach ($empresas as $empresa) {
            $empresa = (object)$empresa;
            DB::connection('mysql')->table('empresas')->insert([
                'nome' => $empresa->nome,
                'pessoal_Contacto' => $empresa->pessoal_Contacto,
                'endereco' => $empresa->endereco,
                'empresa_id' => auth()->user()->empresa_id??2,
                'pais_id' => $empresa->pais_id,
                'saldo' => $empresa->saldo,
                'nif' => $empresa->nif,
                'gestor_cliente_id' => $empresa->gestor_cliente_id,
                'tipo_cliente_id' => $empresa->tipo_cliente_id,
                'tipo_regime_id' => $empresa->tipo_regime_id,
                'logotipo' => $empresa->logotipo,
                'website' => $empresa->website,
                'email' => $empresa->email,
                'referencia' => $empresa->referencia,
                'pessoa_de_contacto' => $empresa->pessoa_de_contacto,
                'status_id' => $empresa->status_id,
                'canal_id' => $empresa->canal_id,
                'user_id' => $empresa->user_id,
                'cidade' => $empresa->cidade,
                'file_alvara' => $empresa->file_alvara,
                'file_nif' => $empresa->file_nif,
                'licenca' => $empresa->licenca,
                'venda_online' => $empresa->venda_online,
                'ultimo_acesso' => $empresa->ultimo_acesso,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
