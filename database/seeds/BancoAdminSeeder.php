<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BancoAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bancos = [
            [
                'uuid'=> Str::uuid(),
                'designacao'=> 'BANCO ECONÓMICO',
                'sigla'=> 'BE',
                'status_id'=> 1,
                'canal_id'=> 1,
                'user_id'=> 1,
            ],
            [
                'uuid'=> Str::uuid(),
                'designacao'=> 'BANCO ANGOLANO DE INVESTIMENTOS',
                'sigla'=> 'BAI',
                'status_id'=> 1,
                'canal_id'=> 1,
                'user_id'=> 1,
            ]
        ];

        foreach ($bancos as $banco){
            $banco = (object)$banco;
            DB::connection('mysql')->table('bancos')->insert([
                'uuid'=> $banco->uuid,
                'designacao'=> $banco->designacao,
                'sigla'=> $banco->sigla,
                'status_id'=> $banco->status_id,
                'canal_id'=> $banco->canal_id,
                'user_id'=> $banco->user_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
