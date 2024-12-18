<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSenhaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statusSenha = [
            'Vulnerável','Segura'
        ];
        foreach ($statusSenha as $designacao){
            DB::connection('mysql')->table('status_senha')->insert([
                'designacao' => $designacao,
            ]);
        }
    }
}
