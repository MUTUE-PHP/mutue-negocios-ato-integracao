<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AtivacaoLicencaAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('mysql')->table('activacao_licencas')->insert([
            'licenca_id' => 4,
            'empresa_id' => 2,
            'pagamento_id' => 1,
            'data_inicio' => Carbon::now(),
            'data_fim' => null,
            'data_activacao' => Carbon::now(),
            'user_id' => 1,
            'operador' => 'Paulo João',
            'canal_id' => 2,
            'status_licenca_id' => 1,
            'data_rejeicao' => null,
            'observacao' => null,
            'data_notificaticao' => null,
            'notificacaoFimLicenca' => null,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
    }
}
