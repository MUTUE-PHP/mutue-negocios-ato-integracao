<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LicencaAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $licencas = [
            [
                'uuid' => Str::uuid(),
                'designacao'=> 'Grátis',
                'tipo_licenca_id' => 1,
                'status_licenca_id'=> 1,
                'canal_id'=> 3,
                'user_id'=> 1,
                'descricao'=> 'Plano Grátis',
                'valor'=> 0,
                'tipo_taxa_id'=> 1,
                'limite_usuario'=> 2
            ],
            [
                'uuid' => Str::uuid(),
                'designacao'=> 'Mensal',
                'tipo_licenca_id' => 2,
                'status_licenca_id'=> 1,
                'canal_id'=> 3,
                'user_id'=> 1,
                'descricao'=> 'Plano Mensal',
                'valor'=> 9025,
                'tipo_taxa_id'=> 1,
                'limite_usuario'=> 2
            ],
            [
                'uuid' => Str::uuid(),
                'designacao'=> 'Anual',
                'tipo_licenca_id' => 3,
                'status_licenca_id'=> 1,
                'canal_id'=> 3,
                'user_id'=> 1,
                'descricao'=> 'Plano Anual',
                'valor'=> 108300,
                'tipo_taxa_id'=> 1,
                'limite_usuario'=> 2
            ],
            [
                'uuid' => Str::uuid(),
                'designacao'=> 'Definitivo',
                'tipo_licenca_id' => 4,
                'status_licenca_id'=> 1,
                'canal_id'=> 3,
                'user_id'=> 1,
                'descricao'=> 'Plano Definitivo',
                'valor'=> 216600,
                'tipo_taxa_id'=> 1,
                'limite_usuario'=> 2
            ],

        ];

        foreach ($licencas as $licenca) {
            $licenca = (object)$licenca;
            DB::connection('mysql')->table('licencas')->insert([
                'uuid' => $licenca->uuid,
                'designacao'=> $licenca->designacao,
                'tipo_licenca_id' => $licenca->tipo_licenca_id,
                'status_licenca_id'=> $licenca->status_licenca_id,
                'canal_id'=> $licenca->canal_id,
                'user_id'=> $licenca->user_id,
                'descricao'=> $licenca->descricao,
                'valor'=> $licenca->valor,
                'tipo_taxa_id'=> $licenca->tipo_taxa_id,
                'limite_usuario'=> $licenca->limite_usuario
            ]);
        }
    }
}
