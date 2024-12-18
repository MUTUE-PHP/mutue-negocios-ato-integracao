<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormaPagamentoAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $formasPagamentos = [
            'TPA','DEPÓSITO','TRANSFERÊNCIA','MULTICAIXA'
        ];
        foreach ($formasPagamentos as $designacao){
            DB::connection('mysql')->table('formas_pagamentos')->insert([
                'descricao' => $designacao,
            ]);
        }
    }
}
